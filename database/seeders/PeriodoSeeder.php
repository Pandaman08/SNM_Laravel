<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Periodo;
use App\Models\AnioEscolar;
use Carbon\Carbon;

class PeriodoSeeder extends Seeder
{
    public function run(): void
    {
        $currentYear = date('Y');
        $pastYear = $currentYear - 1;

        // Buscar los años escolares creados
        $anioPasado = AnioEscolar::where('anio', (string)$pastYear)->first();
        $anioActual = AnioEscolar::where('anio', (string)$currentYear)->first();

        // Periodos para el año pasado (Finalizados)
        if ($anioPasado) {
            $periodosPasados = [
                [
                    'nombre' => 'I',
                    'fecha_inicio' => "$pastYear-03-05 00:00:00",
                    'fecha_fin' => "$pastYear-05-15 00:00:00",
                    'estado' => 'Finalizado',
                    'id_anio_escolar' => $anioPasado->id_anio_escolar
                ],
                [
                    'nombre' => 'II',
                    'fecha_inicio' => "$pastYear-05-20 00:00:00",
                    'fecha_fin' => "$pastYear-07-25 00:00:00",
                    'estado' => 'Finalizado',
                    'id_anio_escolar' => $anioPasado->id_anio_escolar
                ],
                [
                    'nombre' => 'III',
                    'fecha_inicio' => "$pastYear-08-05 00:00:00",
                    'fecha_fin' => "$pastYear-10-10 00:00:00",
                    'estado' => 'Finalizado',
                    'id_anio_escolar' => $anioPasado->id_anio_escolar
                ],
                [
                    'nombre' => 'IV',
                    'fecha_inicio' => "$pastYear-10-15 00:00:00",
                    'fecha_fin' => "$pastYear-12-20 00:00:00",
                    'estado' => 'Finalizado',
                    'id_anio_escolar' => $anioPasado->id_anio_escolar
                ]
            ];

            foreach ($periodosPasados as $periodo) {
                Periodo::firstOrCreate(
                    [
                        'nombre' => $periodo['nombre'],
                        'id_anio_escolar' => $periodo['id_anio_escolar']
                    ],
                    $periodo
                );
            }
        }

        // Periodos para el año actual
        if ($anioActual) {
            $periodosActuales = [
                [
                    'nombre' => 'I',
                    'fecha_inicio' => "$currentYear-03-05 00:00:00",
                    'fecha_fin' => "$currentYear-05-15 00:00:00",
                    'estado' => 'Proceso', // O el estado que corresponda según la fecha actual
                    'id_anio_escolar' => $anioActual->id_anio_escolar
                ],
                [
                    'nombre' => 'II',
                    'fecha_inicio' => "$currentYear-05-20 00:00:00",
                    'fecha_fin' => "$currentYear-07-25 00:00:00",
                    'estado' => 'Proceso',
                    'id_anio_escolar' => $anioActual->id_anio_escolar
                ],
                [
                    'nombre' => 'III',
                    'fecha_inicio' => "$currentYear-08-05 00:00:00",
                    'fecha_fin' => "$currentYear-10-10 00:00:00",
                    'estado' => 'Proceso',
                    'id_anio_escolar' => $anioActual->id_anio_escolar
                ],
                [
                    'nombre' => 'IV',
                    'fecha_inicio' => "$currentYear-10-15 00:00:00",
                    'fecha_fin' => "$currentYear-12-20 00:00:00",
                    'estado' => 'Proceso',
                    'id_anio_escolar' => $anioActual->id_anio_escolar
                ]
            ];

            foreach ($periodosActuales as $periodo) {
                Periodo::firstOrCreate(
                    [
                        'nombre' => $periodo['nombre'],
                        'id_anio_escolar' => $periodo['id_anio_escolar']
                    ],
                    $periodo
                );
            }
        }
    }
}