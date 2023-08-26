<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            PermissionSeeder::class, // Seed Permissions first, don't ask questions
            RoleSeeder::class,
            // PatientSeeder::class,
            AdminSeeder::class,
            DoctorSeeder::class,
            // AppointmentSeeder::class,
        ]);
    }
}
