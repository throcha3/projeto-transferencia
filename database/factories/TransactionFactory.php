<?php

namespace Database\Factories;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'payee_id'=> Account::factory()->create([
                'type' => Account::TYPE_STOREKEEPER
            ]),
            'payer_id' => Account::factory()->create([
                'type' => Account::TYPE_COMMON
            ]),
            'value'=>  $this->faker->randomFloat(2, 0, 5000),
        ];
    }
}
