<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Periodo;
use Carbon\Carbon;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        $periodos = [
            [
                'nombre' => 'I',
                'fecha_inicio' => '2025-03-05 00:00:00',
                'fecha_fin' => '2025-05-05 00:00:00',
                'estado' => 'Proceso',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'II',
                'fecha_inicio' => '2025-06-06 00:00:00',

                'fecha_fin' => '2025-08-08 00:00:00',

                'estado' => 'Proceso',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'III',
                'fecha_inicio' => '2025-08-05 00:00:00',
                'fecha_fin' => '2025-10-05 00:00:00',

                'estado' => 'Proceso',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'nombre' => 'IV',
                'fecha_inicio' => '2025-10-05 00:00:00',
                'fecha_fin' => '2025-12-20 00:00:00',
                'estado' => 'Proceso',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        foreach ($periodos as $periodo) {
            Periodo::create($periodo);
        }
    }
}