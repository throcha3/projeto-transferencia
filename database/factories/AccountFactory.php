<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'type'=> $this->faker->numberBetween(0,1),
            'name' => $this->faker->name(),
            'document'=> $this->faker->unique()->randomNumber(9),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt(Str::Random(25)),
            'current_balance' => $this->faker->randomFloat(2, 0, 5000),
        ];
    }
}
