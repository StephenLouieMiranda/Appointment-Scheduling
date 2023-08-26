<?php

namespace Database\Factories;

use Faker\Provider\en_PH\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Faker\Provider\en_PH\PhoneNumber;

class PatientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'patient_code' => Str::upper($this->faker->numerify("P#######{$this->faker->randomLetter()}")),
            'email' => $this->faker->safeEmail(),
            'password' => "$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi", // password
            'gender' => $gender = $this->faker->randomElement(['male', 'female']),
            'first_name' => $gender == 'male' ? $this->faker->firstNameMale() : $this->faker->firstNameFemale(),
            'middle_name' => $this->faker->lastName(),
            'last_name' => $this->faker->lastName(),
            'birth_date' => $this->faker->date('Y-m-d', now()->subYear(1)),
            'birth_place' => Address::municipality() . " " . Address::province(),
            'contact_number' => PhoneNumber::mobileNumber(),
            'house_number' => Address::buildingNumber(),
            'barangay' => Address::barangay(),
            'municipality' => Address::municipality(),
            'province' => Address::province(),
            'postal_code' => Address::postcode(),
            'status' => 'active',
            'remember_token' => Str::random(10),
        ];
    }
}
