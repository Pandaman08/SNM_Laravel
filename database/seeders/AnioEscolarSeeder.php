<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AnioEscolar;

class AnioEscolarSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = date('Y');
        $pastYear = $currentYear - 1;

        $anios = [
            [
                'anio' => (string)$pastYear,
                'descripcion' => "Año Escolar $pastYear",
                'fecha_inicio' => "$pastYear-03-01",
                'fecha_fin' => "$pastYear-12-20",
                'estado' => 'Finalizado'
            ],
            [
                'anio' => (string)$currentYear,
                'descripcion' => "Año Escolar $currentYear",
                'fecha_inicio' => "$currentYear-03-01",
                'fecha_fin' => "$currentYear-12-20",
                'estado' => 'Activo'
            ]
        ];

        foreach ($anios as $anio) {
            AnioEscolar::firstOrCreate(
                ['anio' => $anio['anio']], 
                $anio
            );
        }
    }
}