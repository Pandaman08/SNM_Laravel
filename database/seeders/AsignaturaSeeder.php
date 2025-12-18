<?php

namespace Database\Seeders;

use App\Models\Asignatura;
use App\Models\AsignaturaDocente;
use App\Models\Docente;
use App\Models\Grado;
use Illuminate\Database\Seeder;

class AsignaturaSeeder extends Seeder
{
    public function run()
    {
        // Primero crear todas las asignaturas (factory ya genera las asignaturas)
        Asignatura::factory(60)->create();

        // -----------------------
        // PRIMARIA: un docente por grado (no repetir docente entre grados primarios)
        // -----------------------
        $gradosPrimaria = Grado::with('nivelEducativo')
            ->whereHas('nivelEducativo', fn($q) => $q->where('nombre', 'Primaria'))
            ->get();

        // guardamos códigos de docentes de primaria ya asignados a un grado
        $docentesPrimariaAsignados = [];

        foreach ($gradosPrimaria as $grado) {
            $nivelId = $grado->nivelEducativo->id_nivel_educativo;

            // docentes del nivel Primaria
            $docentesPrimaria = Docente::where('nivel_educativo_id', $nivelId)->get();

            // filtrar docentes que aún no fueron asignados a otro grado primario
            $disponibles = $docentesPrimaria->filter(function ($d) use ($docentesPrimariaAsignados) {
                return ! in_array($d->codigo_docente, $docentesPrimariaAsignados);
            })->values();

            if ($disponibles->isEmpty()) {
                // No hay docentes sin asignar: saltamos (evita que un docente primario quede en varios grados)
                continue;
            }

            // Seleccionar UN docente disponible para este grado
            $docenteAsignado = $disponibles->shuffle()->first();
            $docentesPrimariaAsignados[] = $docenteAsignado->codigo_docente;

            // Obtener todas las asignaturas de este grado (excepto Educación Física e Inglés)
            $asignaturas = Asignatura::where('id_grado', $grado->id_grado)
                ->whereNotIn('nombre', ['Educación Física', 'Inglés'])
                ->get();

            // Asignar todas las asignaturas de ese grado al docente seleccionado
            foreach ($asignaturas as $asignatura) {
                $existe = AsignaturaDocente::where('codigo_asignatura', $asignatura->codigo_asignatura)
                    ->where('codigo_docente', $docenteAsignado->codigo_docente)
                    ->exists();

                if (! $existe) {
                    AsignaturaDocente::create([
                        'codigo_asignatura' => $asignatura->codigo_asignatura,
                        'codigo_docente' => $docenteAsignado->codigo_docente,
                        'fecha' => now()->toDateString()
                    ]);
                }
            }
        }

        // -----------------------
        // SECUNDARIA: asignar 1-2 docentes por asignatura (sin cambios lógicos)
        // -----------------------
        $gradosSecundaria = Grado::with('nivelEducativo')
            ->whereHas('nivelEducativo', fn($q) => $q->where('nombre', 'Secundaria'))
            ->get();

        foreach ($gradosSecundaria as $grado) {
            $nivelId = $grado->nivelEducativo->id_nivel_educativo;
            $docentesSecundaria = Docente::where('nivel_educativo_id', $nivelId)->get();

            if ($docentesSecundaria->isEmpty()) continue;

            $asignaturas = Asignatura::where('id_grado', $grado->id_grado)
                ->whereNotIn('nombre', ['Educación Física', 'Inglés'])
                ->get();

            foreach ($asignaturas as $asignatura) {
                $cantidad = rand(1, 2);
                $seleccionados = $docentesSecundaria->shuffle()->take($cantidad);

                foreach ($seleccionados as $docente) {
                    $existe = AsignaturaDocente::where('codigo_asignatura', $asignatura->codigo_asignatura)
                        ->where('codigo_docente', $docente->codigo_docente)
                        ->exists();

                    if (! $existe) {
                        AsignaturaDocente::create([
                            'codigo_asignatura' => $asignatura->codigo_asignatura,
                            'codigo_docente' => $docente->codigo_docente,
                            'fecha' => now()->toDateString()
                        ]);
                    }
                }
            }
        }
    }
}