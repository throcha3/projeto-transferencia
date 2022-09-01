<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_store_transfer_when_request_is_valid()
    {
        $commonAccount =  Account::factory()->create([
            'type' => Account::TYPE_COMMON,
            'current_balance' => 200
        ]);

        $storeKeeperAccount =  Account::factory()->create([
            'type' => Account::TYPE_STOREKEEPER,
            'current_balance' => 1631.30
        ]);


        $response = $this->post('/api/transfer', [
            'payer_id' => $commonAccount->getKey(),
            'payee_id' => $storeKeeperAccount->getKey(),
            'value' => 15
        ]);

        $response->assertStatus(201);

        $commonAccount = $commonAccount->find($commonAccount->getKey());
        $storeKeeperAccount = $storeKeeperAccount->find($storeKeeperAccount->getKey());

        $this->assertEquals(185, $commonAccount->current_balance);
        $this->assertEquals(1646.3, $storeKeeperAccount->current_balance);

        $transaction = Transaction::first();

        $this->assertEquals($commonAccount->id, $transaction->payer_id);
        $this->assertEquals($storeKeeperAccount->id, $transaction->payee_id);
        $this->assertEquals(15, $transaction->value);
    }

    /**
     * @test
     */
    public function should_not_store_transfer_when_required_fields_are_not_there()
    {
        Account::factory(2)->create([
            'type' => Account::TYPE_COMMON,
            'current_balance' => 200
        ]);

        $response = $this->post('/api/transfer', [], ['Accept' => 'aplication/json']);

        $response->assertStatus(422);
    }

}
