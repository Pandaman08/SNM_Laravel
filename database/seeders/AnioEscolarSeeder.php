<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnioEscolar;

class AnioEscolarSeeder extends Seeder
{
    public function run(): void
    {
        $anios = [
            [
                'anio' => 2024,
                'descripcion' => 'Año Escolar 2024',
                'fecha_inicio' => '2024-03-01',
                'fecha_fin' => '2024-12-20',
                'estado' => 'Activo'
            ],
            [
                'anio' => 2025,
                'descripcion' => 'Año Escolar 2025',
                'fecha_inicio' => '2025-03-01',
                'fecha_fin' => '2025-12-20',
                'estado' => 'Activo'
            ]
        ];

        foreach ($anios as $anio) {
            AnioEscolar::create($anio);
        }
    }
}