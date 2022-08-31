<?php

namespace App\Service;

use App\Exceptions\TransferOutOfRulesException;
use App\Jobs\SendTransactionNotification;
use App\Models\Account;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TransactionService
{
    /**
     * Execute all processes of transfering money
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public static function makeTransfer(array $transaction)
    {
        DB::beginTransaction();

        try {
            self::validateTransactionRules($transaction);

            $transaction = Transaction::create([
                'payer_id' => $transaction['payer_id'],
                'payee_id' => $transaction['payee_id'],
                'value' => $transaction['value'],
            ]);

            AccountService::movementAccountValuesFromTransaction($transaction);
            SendTransactionNotification::dispatch($transaction);
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            return ['message' => $th->getMessage(), 'http_code'=> Response::HTTP_INTERNAL_SERVER_ERROR];
        }

        return ['message' => 'Transfer performed successfully', 'http_code' => Response::HTTP_CREATED];
    }

    /**
     * Validate all business rules required for a transfer
     *
     * @param array $transaction
     * @return void
     */
    public static function validateTransactionRules($transaction)
    {
        $payerAccount = Account::find($transaction['payer_id']);

        if (
            ! self::isPayerTypeValid($payerAccount)
            || ! self::payerHasEnoughBalance($payerAccount->current_balance, $transaction['value'])
            || ! self::externalAuthorizer()
        ) {
            throw new TransferOutOfRulesException('Transaction not allowed');
        }
    }

    /**
     * Check if Payee is a Common User
     *
     * @param Account $payerAccount
     * @return boolean
     * @throws Exception
     */
    public static function isPayerTypeValid(Account $payerAccount)
    {
        if (! $payerAccount->isAllowedToBePayer($payerAccount->type)) {
            return false;
        }

        return true;
    }

    /**
     * Check if payer has enough account balance to make the transaction
     *
     * @param float $payerCurrentBalance
     * @param float $transactionValue
     * @return boolean
     * @throws Exception
     */
    public static function payerHasEnoughBalance($payerCurrentBalance, $transactionValue)
    {
        if($payerCurrentBalance - $transactionValue < 0) {
            return false;
        }

        return true;
    }

    /**
     * Check if the third party service authorize the transaction
     *
     * @return boolean
     */
    public static function externalAuthorizer()
    {
        $response = Http::get(Account::URL_EXTERNAL_AUTHORIZER);
        return $response->status() == 200
            ? true
            : false;
    }
}
