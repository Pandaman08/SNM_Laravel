<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TipoCalificacion;

class TipoCalificacionSeeder extends Seeder
{
    public function run()
    {
        $datos = [
            [
                'codigo' => 'AD',
                'nombre' => 'LOGRO DESTACADO',
                'descripcion' => 'Cuando el estudiante evidencia un nivel superior a lo esperado respecto a la competencia. Esto quiere decir que demuestra aprendizajes que van más allá del nivel esperado.'
            ],
            [
                'codigo' => 'A',
                'nombre' => 'LOGRO ESPERADO',
                'descripcion' => 'Cuando el estudiante evidencia el nivel esperado respecto a la competencia, demostrando manejo satisfactorio en todas las tareas propuestas y en el tiempo programado.'
            ],
            [
                'codigo' => 'B',
                'nombre' => 'EN PROCESO',
                'descripcion' => 'Cuando el estudiante está próximo o cerca al nivel esperado respecto a la competencia, para lo cual requiere acompañamiento durante un tiempo razonable para lograrlo.'
            ],
            [
                'codigo' => 'C',
                'nombre' => 'EN INICIO',
                'descripcion' => 'Cuando el estudiante muestra progreso mínimo en una competencia de acuerdo al nivel esperado. Evidencia con frecuencia dificultades en el desarrollo de las tareas, por lo que necesita mayor tiempo de acompañamiento e intervención del docente.'
            ],
        ];

        foreach ($datos as $dato) {
            TipoCalificacion::create($dato);
        }
    }
}
