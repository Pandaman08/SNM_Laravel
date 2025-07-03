<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeriodosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('periodos')->insert([
            [
                'id_periodo' => 3,
                'nombre' => 'I',
                'created_at' => '2025-06-11 22:38:45',
                'updated_at' => '2025-06-11 22:38:45',
            ],
            [
                'id_periodo' => 4,
                'nombre' => 'II',
                'created_at' => '2025-06-11 22:38:50',
                'updated_at' => '2025-06-11 22:38:50',
            ],
            [
                'id_periodo' => 5,
                'nombre' => 'III',
                'created_at' => '2025-06-11 22:38:55',
                'updated_at' => '2025-06-11 22:38:55',
            ],
        ]);
    }
}
