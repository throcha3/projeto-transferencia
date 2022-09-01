<?php

namespace App\Repository;

use App\Models\Account;
use App\Models\Transaction;

class TransactionRepository
{

    /**
     * Create a Transaction row
     *
     * @param  \App\Http\Requests\StoreTransactionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function createTransaction(array $transaction)
    {
        return Transaction::create([
            'payer_id' => $transaction['payer_id'],
            'payee_id' => $transaction['payee_id'],
            'value' => $transaction['value'],
        ]);
    }
}
