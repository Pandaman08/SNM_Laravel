<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocenteEspecialidadSeeder extends Seeder
{
    public function run(): void
    {
        $relaciones = [
            [1, 16], [2, 16], [3, 16], [4, 16], [5, 16], [6, 16], [7, 16], [8, 16], [9, 16],
            [10, 7], [10, 16],
            [11, 6], [11, 16],
            [12, 9], [12, 16],
            [13, 8], [13, 16],
            [14, 10], [14, 16],
            [15, 11], [15, 16],
            [16, 12], [16, 16],
            [17, 14], [17, 16],
            [18, 15],
            [19, 6], [19, 17],
            [20, 6], [20, 17],
            [21, 6], [21, 17],
            [22, 7],
            [23, 7],
            [24, 7],
            [25, 8],
            [26, 8],
            [27, 8],
            [28, 9], [28, 17],
            [29, 10],
            [30, 12],
        ];

        foreach ($relaciones as $relacion) {
            list($codigo_docente, $id_especialidad) = $relacion;
            
            DB::table('docente_especialidad')->updateOrInsert(
                [
                    'codigo_docente' => $codigo_docente,
                    'id_especialidad' => $id_especialidad
                ],
                [
                    'estado' => 'Activo'
                ]
            );
        }
    }
}