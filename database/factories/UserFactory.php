<?php

namespace Database\Factories;

use Faker\Provider\en_PH\PhoneNumber;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_code' => Str::upper($this->faker->numerify("{$this->faker->randomLetter()}#######{$this->faker->randomLetter()}")),
            'email' => $this->faker->unique()->safeEmail(),
            'gender' => $gender = $this->faker->randomElement(['male', 'female']),
            'first_name' => $gender == 'male' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale(),
            'middle_name' => $this->faker->lastName(),
            'last_name' => $this->faker->lastName(),
            'contact_number' => PhoneNumber::mobileNumber(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
    // /**
    //  * Indicate that the model's email address should be unverified.
    //  * @return \Illuminate\Database\Eloquent\Factories\Factory
    //  */
    // public function unverified()
    // {
    //     return $this->state(function (array $attributes) {
    //         return [
    //             'email_verified_at' => null,
    //         ];
    //     });
    // }
}
