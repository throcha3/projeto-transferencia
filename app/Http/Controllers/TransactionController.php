<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Models\Transaction;
use App\Service\TransactionService;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct()
    {
        $this->transactionService = new TransactionService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function makeTransfer(StoreTransactionRequest $transaction) {
        $return = $this->transactionService->makeTransfer($transaction->validated());

        return response()->json([
            'message' => $return['message']
        ], $return['http_code']);
    }
}
