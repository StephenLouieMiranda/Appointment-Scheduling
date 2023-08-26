<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = User::factory(3)->create();
        foreach($users as $user){
            return [
                'user_code' => $user->user_code,
                'specialization' => $this->faker->randomElement(['Dermatology', 'Gynecology', 'Physician', 'Pediatrics', 'Optemetry']),
                'license_number' => $this->faker->numerify("{$this->faker->randomElement(['D', 'G', 'P', 'O',])}{$this->faker->randomElement(['N', 'H', 'C', 'D', 'T'])}####"),
            ];
        }
    }
}
