<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\Docente;
use App\Models\Seccion;
use Illuminate\Database\Seeder;

class AsignaturaSeeder extends Seeder
{
    public function run()
    {
        Asignatura::factory(120)->create()->each(function ($asignatura) {
            $grado = $asignatura->grado;
            $nombre = $grado->nivelEducativo->nombre;

            if ($nombre === 'Primaria') {
                // Primaria: asignar SOLO al docente de una sección de este grado
                $seccion = Seccion::where('id_grado', $grado->id_grado)->first();
                if (!$seccion) return;

                $docente = $seccion->todosLosDocentes()->first();
                if ($docente) {
                    $asignatura->docentes()->attach($docente->codigo_docente, ['fecha' => now()]);
                }
            } else {
                // Secundaria: asignar docentes del mismo nivel (pueden estar en cualquier grado)
                $docentesDelNivel = Docente::where('nivel_educativo_id', $grado->nivel_educativo_id)->get();
                if ($docentesDelNivel->isEmpty()) return;

                $cantidad = rand(1, 2);
                $seleccionados = $docentesDelNivel->shuffle()->take($cantidad);
                foreach ($seleccionados as $docente) {
                    $asignatura->docentes()->attach($docente->codigo_docente, ['fecha' => now()]);
                }
            }
        });
    }
}