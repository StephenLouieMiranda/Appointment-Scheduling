<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            'super_admin',
            'admin',
            'doctor',
            'patient',
        ];

        $permissions = Permission::all();
        $permissions_array = [];
        foreach ($permissions as $key => $permission) {
            $divide = explode(".", $permission->name);
            $permissions_array[$divide[0]][] = $permission;
        }

        foreach ($roles as $key => $item) {
            $role = Role::firstOrCreate(['name' => $item]);
            $assignable = [];
            switch ($item) {
                case 'super_admin':
                case 'admin':
                    $role->syncPermissions($permissions);
                    break;
                case 'doctor':
                    $assignable = array_merge($assignable, $permissions_array['appointments']);
                    $assignable = array_merge($assignable, $permissions_array['patients']);
                    $assignable = array_merge($assignable, $permissions_array['medical_records']);
                    $role->syncPermissions($assignable);
                    break;
                case 'patient':
                    $assignable = array_merge($assignable, $permissions_array['patients']);
                    $assignable = array_merge($assignable, $permissions_array['medical_records']);
                    $assignable = array_merge($assignable, $permissions_array['appointments']);
                    $role->syncPermissions($assignable);
                    break;
                default: break;
            }
        }
    }
}
