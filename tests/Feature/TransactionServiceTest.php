<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Service\TransactionService;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider payerTypesValidation
     */
    public function should_allow_transaction_only_when_payer_is_common_account($type, $expectedResult)
    {
        $transactionService = new TransactionService();
        $commonAccount =  Account::factory()->create([
            'type' => $type
        ]);

        $transaction = $transactionService->isPayerTypeValid($commonAccount);

        $this->assertEquals($expectedResult, $transaction);
    }

    private function payerTypesValidation()
    {
        return [
            [
                'type' => Account::TYPE_COMMON,
                'expectedResult' => true,
            ],
            [
                'type' => Account::TYPE_STOREKEEPER,
                'expectedResult' => false,
            ]
        ];
    }

    /**
     * @test
     * @dataProvider payerCurrentBalanceValidation
     */
    public function should_allow_transaction_only_when_payer_have_enough_balance($currentBalance, $expectedResult)
    {
        $transactionService = new TransactionService();
        $commonAccount =  Account::factory()->create([
            'type' => Account::TYPE_COMMON,
            'current_balance' => $currentBalance
        ]);

        $transaction = $transactionService->payerHasEnoughBalance($commonAccount->current_balance, 500);

        $this->assertEquals($expectedResult, $transaction);
    }

    private function payerCurrentBalanceValidation()
    {
        return [
            [
                'currentBalance' => 1000,
                'expectedResult' => true,
            ],
            [
                'currentBalance' => 200,
                'expectedResult' => false,
            ]
        ];
    }
}
