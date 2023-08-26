<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'transaction_code' => Str::random(13),
            'patient_code' => Str::random(13),
            'amount' => $this->faker->randomFloat(2, 100, 1000),
            'service' => 'test service',
            'status' => $this->faker->randomElement(['paid', 'unpaid', 'cancelled']),
            // random date in the current month
            'created_at' =>  $this->faker->dateTimeBetween('-1 month', 'now')
        ];
    }
}
