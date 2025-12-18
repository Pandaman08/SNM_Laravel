<?php
namespace Database\Seeders;

// database/seeders/SeccionDocenteSeeder.php

use App\Models\Seccion;
use App\Models\Docente;
use App\Models\AsignaturaDocente;
use Illuminate\Database\Seeder;

class SeccionDocenteSeeder extends Seeder
{
    public function run()
    {
        // Cargar asignaciones y relaciones necesarias
        $asignacionesDocentes = AsignaturaDocente::with(['asignatura.grado.nivelEducativo'])->get();

        // Agrupar por docente: guardar grados y nivel
        $docentesPorGrado = [];

        foreach ($asignacionesDocentes as $asignacion) {
            if (! optional($asignacion->asignatura)->grado) continue;

            $codigoDocente = $asignacion->codigo_docente;
            $grado = $asignacion->asignatura->grado;
            $gradoId = $grado->id_grado;
            $nivelNombre = $grado->nivelEducativo->nombre ?? null;

            if (! isset($docentesPorGrado[$codigoDocente])) {
                $docentesPorGrado[$codigoDocente] = [];
            }

            // Evitar duplicados de grado para un mismo docente
            $existeGrado = collect($docentesPorGrado[$codigoDocente])->contains(fn($g) => $g['id_grado'] === $gradoId);
            if (! $existeGrado) {
                $docentesPorGrado[$codigoDocente][] = [
                    'id_grado' => $gradoId,
                    'nivel' => $nivelNombre
                ];
            }
        }

        // Asignar secciones según la información agregada
        foreach ($docentesPorGrado as $codigoDocente => $gradosInfo) {
            $docente = Docente::find($codigoDocente);
            if (! $docente) continue;

            // Obtener si el docente ya tiene sección asignada (si es primaria no le daremos más de una sección)
            $tieneSeccionAsignada = $docente->seccionesDocentes()->exists();

            foreach ($gradosInfo as $gradoInfo) {
                $gradoId = $gradoInfo['id_grado'];
                $nivelNombre = $gradoInfo['nivel'];

                $secciones = Seccion::where('id_grado', $gradoId)->get();
                if ($secciones->isEmpty()) continue;

                if ($nivelNombre === 'Primaria') {
                    // Si el docente ya tiene una sección (de cualquier grado) saltamos: docente de primaria debe tener solo UNA sección
                    if ($tieneSeccionAsignada) break;

                    // Buscar secciones del grado que NO tengan docente asignado (prioridad)
                    $seccionLibre = $secciones->first(function ($s) {
                        return ! $s->seccionesDocentes()->where('estado', true)->exists();
                    });

                    // Si no hay seccion libre, intentar cualquiera (fallback)
                    if (! $seccionLibre) {
                        $seccionLibre = $secciones->shuffle()->first();
                    }

                    // Verificar si el docente ya está asignado a esa sección (por seguridad)
                    if ($seccionLibre && ! $seccionLibre->seccionesDocentes()->where('codigo_docente', $codigoDocente)->exists()) {
                        $seccionLibre->todosLosDocentes()->attach($codigoDocente, [
                            'estado' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                        // marcar que el docente ya tiene sección asignada
                        $tieneSeccionAsignada = true;
                        // como docente de primaria solo debe tener una sección, rompemos
                        break;
                    }
                } else {
                    // Secundaria: comportamiento flexible — asignar 1 a N secciones aleatorias,
                    // pero evitamos duplicar la misma asignación.
                    $maxSecciones = $secciones->count();
                    $cantidadSecciones = rand(1, max(1, min(3, $maxSecciones))); // limitar para no saturar

                    $seccionesSeleccionadas = $secciones->shuffle()->take($cantidadSecciones);

                    foreach ($seccionesSeleccionadas as $seccion) {
                        $existe = $seccion->todosLosDocentes()
                            ->where('secciones_docentes.codigo_docente', $codigoDocente)
                            ->exists();

                        if (! $existe) {
                            $seccion->todosLosDocentes()->attach($codigoDocente, [
                                'estado' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
    }
}