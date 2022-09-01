<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Transaction;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendTransactionNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 99;
    public $maxExceptions = 99;
    public $backoff = [30, 120, 300];

    protected Transaction $transaction;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $response = Http::post(Account::URL_NOTIFICATION, [$this->transaction->toArray()]);

        if ($response->status() <> 201) {
            throw new Exception('Failed to send Payee notification!');
            return $this->release();
        }

        return true;
    }

}
