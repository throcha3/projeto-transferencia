<?php

namespace App\Service;

use App\Models\Account;
use App\Models\Transaction;

class AccountService
{
    public function movementAccountValuesFromTransaction(Transaction $transaction)
    {
        $payerAccount = Account::find($transaction->payer_id);
        $payerAccount->current_balance =- $transaction->value;

        $payeeAccount = Account::find($transaction->payer_id);
        $payeeAccount->current_balance =+ $transaction->value;

        return true;
    }
}
