<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        $permissions = [
            'accounts.*', //CRUD oprations for Accounts
            'accounts.store', // Create new account data
            'accounts.read', // View and/or read account data
            'accounts.update', // Update account data
            'accounts.delete', // Delete account data
            'accounts.forceDelete', // Force delete account data

            'appointments.*', // Read, view, store, update, and cancel appointment
            'appointments.store', // Store new appointment data
            'appointments.all', // Read/view list of all appointment
            'appointments.list', // Read/view list of appointment
            'appointments.publish_link', // Create/Initialize a new consultation link of appointment
            'appointments.update', // Update an appointment data
            'appointments.cancel', // Cancel an appointment

            'patients.*', // CRUD operations for Patient Model
            'patients.read', // View and/or read patient data
            'patients.store', // Add or create new patient
            'patients.update', // Update patient data
            'patients.delete', // Delete patient data
            'patients.forceDelete', // Force delete patient data

            'medical_records.*', // CRUD operations for results module
            'medical_records.store', // Store/add new results data
            'medical_records.read', // View and/or read results data
            'medical_records.update', // Update results data
            'medical_records.delete', // Delete results data
        ];

        foreach ($permissions as $key => $item) {
            Permission::firstOrCreate(['name' => $item]);
        }
    }
}
