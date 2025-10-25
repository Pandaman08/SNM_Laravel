<?php
namespace Database\Seeders;

// database/seeders/SeccionDocenteSeeder.php

use App\Models\Seccion;
use App\Models\Docente;
use Illuminate\Database\Seeder;

class SeccionDocenteSeeder extends Seeder
{
    public function run()
    {
        // Obtener todos los docentes por nivel
        $docentesPrimaria = Docente::where('nivel_educativo_id', function ($query) {
            $query->select('id_nivel_educativo')
                  ->from('niveles_educativos')
                  ->where('nombre', 'Primaria');
        })->get();

        $docentesSecundaria = Docente::where('nivel_educativo_id', function ($query) {
            $query->select('id_nivel_educativo')
                  ->from('niveles_educativos')
                  ->where('nombre', 'Secundaria');
        })->get();

        // Asignar docentes a secciones de PRIMARIA: 1 docente por sección
        $seccionesPrimaria = Seccion::whereHas('grado.nivelEducativo', fn($q) => $q->where('nombre', 'Primaria'))->get();
        $docentesPrimaria = $docentesPrimaria->shuffle()->values();
        $index = 0;

        foreach ($seccionesPrimaria as $seccion) {
            if ($index >= $docentesPrimaria->count()) break;
            $docente = $docentesPrimaria[$index];
            $seccion->todosLosDocentes()->attach($docente->codigo_docente, [
                'estado' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $index++;
        }

        // Asignar docentes a secciones de SECUNDARIA: 3-5 docentes por sección (reutilizando docentes)
        $seccionesSecundaria = Seccion::whereHas('grado.nivelEducativo', fn($q) => $q->where('nombre', 'Secundaria'))->get();

        foreach ($seccionesSecundaria as $seccion) {
            if ($docentesSecundaria->isEmpty()) continue;
            $cantidad = rand(3, 5);
            $seleccionados = $docentesSecundaria->shuffle()->take($cantidad);
            foreach ($seleccionados as $docente) {
                $seccion->todosLosDocentes()->attach($docente->codigo_docente, [
                    'estado' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}