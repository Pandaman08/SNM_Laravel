<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AniosEscolaresSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('anios_escolares')->insert([
            [
                'id_anio_escolar' => 1,
                'anio' => '2025',
                'descripcion' => 'Año escolar 2025',
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'estado' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_anio_escolar' => 2,
                'anio' => '2024',
                'descripcion' => 'Año escolar 2024',
                'fecha_inicio' => null,
                'fecha_fin' => null,
                'estado' => 'Activo',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
