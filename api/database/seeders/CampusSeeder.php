<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CampusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('campuses')->insert([
            ['name' => 'Alangilan Campus', 'code' => 'ALANGILAN'],
            ['name' => 'Balayan Campus', 'code' => 'BALAYAN'],
            ['name' => 'Lobo Campus', 'code' => 'LOBO'],
            ['name' => 'Mabini Campus', 'code' => 'MABINI'],
        ]);
    }
}