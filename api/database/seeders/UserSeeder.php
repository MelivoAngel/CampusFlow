<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@campusflow.com',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'campus_id' => null
            ],
            [
                'name' => 'Lobo Admin',
                'email' => 'lobo.admin@campusflow.com',
                'password' => Hash::make('password'),
                'role' => 'campus_admin',
                'campus_id' => 1
            ]
        ]);
    }
}