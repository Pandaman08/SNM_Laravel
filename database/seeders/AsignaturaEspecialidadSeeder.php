<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsignaturaEspecialidadSeeder extends Seeder
{
    public function run(): void
    {
        $relaciones = [
            // Ciencias Exactas (6)
            [2, 6], [5, 6], [8, 6], [12, 6], [33, 6], [38, 6], [48, 6], [49, 6], [53, 6],
            [13, 6], [52, 6], [43, 6], [45, 6], [56, 6],
            
            // Humanidades (7)
            [6, 7], [17, 7], [47, 7], [18, 7], [1, 7],
            
            // Ciencias Sociales (8)
            [3, 8], [16, 8], [21, 8], [58, 8], [15, 8], [30, 8], [28, 8], [34, 8], [50, 8],
            
            // Ciencias Naturales (9)
            [10, 9], [9, 9], [23, 9], [25, 9], [36, 9], [39, 9],
            
            // Idiomas (10)
            [11, 10], [24, 10], [29, 10], [35, 10], [37, 10], [44, 10], [46, 10], [51, 10], [57, 10],
            
            // Educación Artística (11)
            [14, 11], [31, 11], [40, 11], [41, 11], [32, 11],
            
            // Educación Física y Salud (12)
            [27, 12], [42, 12], [54, 12], [59, 12],
            
            // Tecnología e Informática (13)
            [9, 13], [23, 13], [25, 13], [36, 13], [39, 13],
            
            // Formación Ética y Valores (14)
            [4, 14], [7, 14], [19, 14], [20, 14], [22, 14], [26, 14], [55, 14], [60, 14],
            
            // Psicología y Orientación (15)
            [1, 15],
            
            // Educación Primaria Generalista (16)
            [3, 16], [5, 16], [6, 16], [9, 16], [14, 16], [16, 16], [21, 16], [23, 16], 
            [25, 16], [26, 16], [31, 16], [33, 16], [38, 16], [39, 16], [40, 16], [41, 16],
            [42, 16], [44, 16], [47, 16], [54, 16], [55, 16], [57, 16], [58, 16], [60, 16],
            
            // Ciencias Integradas (17)
            [9, 17], [10, 17], [13, 17], [23, 17], [25, 17], [36, 17], [39, 17], [43, 17],
            [45, 17], [52, 17], [56, 17],
        ];

        foreach ($relaciones as $relacion) {
            list($codigo_asignatura, $id_especialidad) = $relacion;
            
            DB::table('asignatura_especialidad')->updateOrInsert(
                [
                    'codigo_asignatura' => $codigo_asignatura,
                    'id_especialidad' => $id_especialidad
                ],
                [
                    'estado' => 'Activo'
                ]
            );
        }
    }
}