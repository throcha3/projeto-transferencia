<?php

namespace App\Service;

use App\Exceptions\TransferOutOfRulesException;
use App\Jobs\SendTransactionNotification;
use App\Models\Account;
use App\Models\Transaction;
use App\Repository\TransactionRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class TransactionService
{
    private AccountService $accountService;

    public function __construct()
    {
        $this->accountService = new AccountService();
        $this->transactionRepository = new TransactionRepository();
    }

    /**
     * Execute all processes of transfering money
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function makeTransfer(array $transaction)
    {
        DB::beginTransaction();

        try {
            $this->validateTransactionRules($transaction);

            $transactionModel = $this->transactionRepository->createTransaction($transaction);

            $this->accountService->movementAccountValuesFromTransaction($transactionModel);

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
    public function validateTransactionRules($transaction)
    {
        $payerAccount = Account::find($transaction['payer_id']);

        if (
            ! $this->isPayerTypeValid($payerAccount)
            || ! $this->payerHasEnoughBalance($payerAccount->current_balance, $transaction['value'])
            || ! $this->externalAuthorizer()
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
    public function isPayerTypeValid(Account $payerAccount)
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
    public function payerHasEnoughBalance($payerCurrentBalance, $transactionValue)
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
    public function externalAuthorizer()
    {
        $response = Http::get(Account::URL_EXTERNAL_AUTHORIZER);
        return $response->status() == 200
            ? true
            : false;
    }
}
