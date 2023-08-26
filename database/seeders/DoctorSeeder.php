<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Database\Seeder;

class DoctorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // if (env('APP_ENV') === 'production') return;

        $user = User::where('first_name', 'Charles Jaeric')->first();
        Doctor::factory()->create([
            'user_code' => $user->user_code
        ]);
    }
}
