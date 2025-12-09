<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\AsignaturaDocente;
use App\Models\Docente;
use App\Models\Seccion;
use Illuminate\Database\Seeder;

class AsignaturaSeeder extends Seeder
{
    public function run()
    {
        Asignatura::factory(60)->create()->each(function ($asignatura) {
            $grado = $asignatura->grado;
            $nombre = $grado->nivelEducativo->nombre;

            if ($nombre === 'Primaria') {
                // Primaria: asignar SOLO al docente de una sección de este grado
                $seccion = Seccion::where('id_grado', $grado->id_grado)->first();
                if (!$seccion) return;

                $docente = $seccion->todosLosDocentes()->first();
                if ($docente) {
                    AsignaturaDocente::create([
                        'codigo_asignatura' => $asignatura->codigo_asignatura,
                        'codigo_docente' => $docente->codigo_docente,
                        'fecha' => now()->toDateString()
                    ]);
                }
            } else {
                // Secundaria: asignar docentes del mismo nivel (pueden estar en cualquier grado)
                $docentesDelNivel = Docente::where('nivel_educativo_id', $grado->nivel_educativo_id)->get();
                if ($docentesDelNivel->isEmpty()) return;

                $cantidad = rand(1, 2);
                $seleccionados = $docentesDelNivel->shuffle()->take($cantidad);
                foreach ($seleccionados as $docente) {
                    AsignaturaDocente::create([
                        'codigo_asignatura' => $asignatura->codigo_asignatura,
                        'codigo_docente' => $docente->codigo_docente,
                        'fecha' => now()->toDateString()
                    ]);
                }
            }
        });
    }
}