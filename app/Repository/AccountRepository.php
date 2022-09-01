<?php

namespace App\Repository;

use App\Models\Account;
use App\Models\Transaction;

class AccountRepository
{

    /**
     * Debit value from account
     *
     * @param integer $accountId
     * @param float $value
     * @return void
     */
    public function debitAccountBalance(int $accountId = 0, float $value = 0)
    {
        $account = Account::find($accountId);
        return $account->update([
            'value' => $account->current_balance -= $value
        ]);
    }

    /**
     * Credit value in account
     *
     * @param integer $accountId
     * @param float $value
     * @return void
     */
    public function creditAccountBalance(int $accountId = 0, float $value = 0)
    {
        $account = Account::find($accountId);
        return $account->update([
            'value' => $account->current_balance += $value
        ]);
    }
}
