<?php

namespace Database\Seeders;

use App\Domain\Users\Enums\UserRole;
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
                'role' => UserRole::SUPERADMIN->value,
                'campus_id' => null,
                'created_by' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'name' => 'Alangilan Admin',
                'email' => 'alangilan.admin@campusflow.com',
                'password' => Hash::make('password'),
                'role' => UserRole::CAMPUS_ADMIN->value,
                'campus_id' => 1,
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'name' => 'Alangilan Staff',
                'email' => 'alangilan.staff@campusflow.com',
                'password' => Hash::make('password'),
                'role' => UserRole::STAFF->value,
                'campus_id' => 1,
                'created_by' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'name' => 'Alangilan Technician',
                'email' => 'alangilan.tech@campusflow.com',
                'password' => Hash::make('password'),
                'role' => UserRole::FIELD_TECHNICIAN->value,
                'campus_id' => 1,
                'created_by' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'name' => 'Alangilan Calendar Admin',
                'email' => 'alangilan.calendar@campusflow.com',
                'password' => Hash::make('password'),
                'role' => UserRole::CALENDAR_ADMIN->value,
                'campus_id' => 1,
                'created_by' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}