<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Docente;
use App\Models\Seccion;
use App\Models\SeccionDocente;

class SeccionDocenteSeeder extends Seeder
{
    public function run()
    {
        // Obtener todos los docentes y secciones
        $docentes = Docente::all();
        $secciones = Seccion::with('grado.nivelEducativo')->get();
        
        if ($docentes->isEmpty() || $secciones->isEmpty()) {
            $this->command->warn('No hay docentes o secciones para asignar.');
            return;
        }
        
        // Asignar docentes a secciones basándose en el nivel educativo
        foreach ($secciones as $seccion) {
            $nivelEducativo = $seccion->grado->nivelEducativo->codigo ?? null;
            
            if ($nivelEducativo === 'INI' || $nivelEducativo === 'PRI') {
                // En inicial y primaria, asignar un docente por sección
                $docente = $docentes->random();
                
                SeccionDocente::firstOrCreate([
                    'id_seccion' => $seccion->id_seccion,
                    'codigo_docente' => $docente->codigo_docente,
                ], [
                    'estado' => true,
                ]);
                
                $this->command->info("Asignado docente {$docente->codigo_docente} a sección {$seccion->seccion} - {$seccion->grado->nombre_completo}");
                
            } elseif ($nivelEducativo === 'SEC') {
                // En secundaria, asignar varios docentes por sección (simulando diferentes materias)
                $numDocentes = min(rand(3, 5), $docentes->count()); // Asegurar que no exceda el número de docentes disponibles
                $docentesAsignados = $docentes->random($numDocentes);
                
                foreach ($docentesAsignados as $docente) {
                    SeccionDocente::firstOrCreate([
                        'id_seccion' => $seccion->id_seccion,
                        'codigo_docente' => $docente->codigo_docente,
                    ], [
                        'estado' => true,
                    ]);
                }
                
                $this->command->info("Asignados {$numDocentes} docentes a sección {$seccion->seccion} - {$seccion->grado->nombre_completo}");
            }
        }
        
        $this->command->info('Asignación de docentes a secciones completada.');
    }
}