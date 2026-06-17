<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('campuses')->insert([
            ['name' => 'Lobo Campus', 'code' => 'LOBO'],
            ['name' => 'Balayan Campus', 'code' => 'BALAYAN'],
            ['name' => 'Alangilan Campus', 'code' => 'ALANGILAN'],
            ['name' => 'Mabini Campus', 'code' => 'MABINI'],
        ]);
    }
}