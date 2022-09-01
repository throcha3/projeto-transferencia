<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Transaction;
use App\Repository\AccountRepository;

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct()
    {
        $this->accountRepository = new AccountRepository();
    }

    /**
     * Debit value from payer's account and credit same value in payee's account
     *
     * @param Transaction $transaction
     * @return void
     */
    public function movementAccountValuesFromTransaction(Transaction $transaction)
    {
        $this->accountRepository->debitAccountBalance($transaction->payer_id, $transaction->value);
        $this->accountRepository->creditAccountBalance($transaction->payee_id, $transaction->value);
    }
}
