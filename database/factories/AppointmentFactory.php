<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'appointment_code' => Str::upper($this->faker->numerify("A{$this->faker->randomLetter()}#######")),
            'patient_code' => $this->faker->numerify("P#######{$this->faker->randomLetter()}"),
            'user_code' => $this->faker->numerify("{$this->faker->randomLetter()}#######{$this->faker->randomLetter()}"),
            'type' => $this->faker->randomElement(['online_consultation', 'outpatient_services']),
            'date' => now()->addDays($this->faker->randomDigit())->format('Y-m-d'),
            'time' => now()->addHours($this->faker->randomNumber(1, true))->format('H:i:s'),
            'complaints' => $this->faker->words(3, true),
            'status' => $this->faker->randomElement(['active', 'pending', 'approved', 'paid', 'pending_payment'])
        ];
    }
}
