<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::firstOrNew(['email' => "jecscor@gmail.com"]);
        if(!empty($user))
        {
            $user->user_code = Str::upper(Str::random(10));
            $user->password = Hash::make("jeffreyadmin123");
            $user->first_name = "Jeffrey Cournery";
            $user->middle_name = '';
            $user->last_name = "Tarog";
            $user->gender = "male";
            $user->contact_number = "09212318163";
            $user->status = "active";
            $user->save();
            $user->assignRole('super_admin');
            $user->assignRole('admin');
        }

        $user = User::firstOrNew(['email' => 'charles.jaeric@gmail.com']);
        if(!empty($user))
        {
            $user->user_code = Str::upper(Str::random(10));
            $user->password = Hash::make("password");
            $user->first_name = "Charles Jaeric";
            $user->middle_name = "Suleik";
            $user->last_name = "Espeleta";
            $user->gender = "male";
            $user->contact_number = "09394251302";
            $user->status = "active";
            $user->save();
            $user->assignRole('doctor');
        }
    }
}
