<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'id'             => 1,
                'name'           => 'Admin',
                'email'          => 'admin@demo.com',
                'password'       => bcrypt('password'),
                'remember_token' => null,
                'phone_number'   => '',
            ],
        ];

        User::insert($users);
    }
}
