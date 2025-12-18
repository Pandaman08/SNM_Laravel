<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asignatura;
use App\Models\Competencia;
use Illuminate\Support\Facades\DB;

class CompetenciaSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener todas las asignaturas
        $asignaturas = Asignatura::all();

        foreach ($asignaturas as $asignatura) {
            // Generar 3 competencias para cada asignatura
            for ($i = 0; $i < 3; $i++) {
                // Decidir si es competencia general o específica (70% específicas, 30% generales)
                $esGeneral = rand(1, 10) <= 3;

                if ($esGeneral) {
                    $this->createCompetenciaGeneral($asignatura);
                } else {
                    $this->createCompetenciaEspecifica($asignatura);
                }
            }
        }

        $this->command->info("Se crearon " . ($asignaturas->count() * 3) . " competencias para {$asignaturas->count()} asignaturas.");
    }

    private function createCompetenciaGeneral($asignatura): void
    {
        $competenciasGenerales = [
            'Se desenvuelve en los entornos virtuales generados por las TIC',
            'Gestiona su aprendizaje de manera autónoma',
            'Desarrolla procesos autónomos de aprendizaje en relación con los recursos existentes',
            'Interactúa a través de sus habilidades sociemocionales',
            'Participa en equipos asumiendo diversos roles',
            'Construye y usa understanding en situaciones problemáticas',
            'Resuelve problemas desde diferentes perspectivas disciplinares',
            'Interpreta la realidad y toma decisiones fundamentadas',
            'Desarrolla el pensamiento crítico y creativo',
            'Aprende a aprender de manera permanente',
        ];

        Competencia::create([
            'codigo_asignatura' => $asignatura->codigo_asignatura,
            'descripcion' => $competenciasGenerales[array_rand($competenciasGenerales)],
        ]);
    }

    private function createCompetenciaEspecifica($asignatura): void
    {
        $nombreAsignatura = $asignatura->nombre;
        
        $competenciasEspecificas = $this->getCompetenciasPorAsignatura($nombreAsignatura);

        Competencia::create([
            'codigo_asignatura' => $asignatura->codigo_asignatura,
            'descripcion' => $competenciasEspecificas[array_rand($competenciasEspecificas)],
        ]);
    }

    private function getCompetenciasPorAsignatura(string $asignatura): array
    {
        $competencias = [
            'Comunicación' => [
                'Se comunica para el desarrollo personal y la convivencia',
                'Comprende críticamente diversos tipos de textos orales',
                'Comprende críticamente diversos tipos de textos escritos',
                'Produce reflexivamente diversos tipos de textos escritos',
                'Utiliza recursos semióticos para interpretar y producir textos',
                'Reflexiona sobre los textos desde su experiencia y contexto',
            ],
            'Matemática' => [
                'Resuelve problemas de regularidad, equivalencia y cambio',
                'Resuelve problemas de cantidad, forma y movimiento',
                'Resuelve problemas de gestión de datos e incertidumbre',
                'Modela objetos y situaciones con formas geométricas',
                'Argumenta afirmaciones sobre relaciones numéricas y algebraicas',
                'Comunica su comprensión sobre los números y las operaciones',
            ],
            'Personal Social' => [
                'Construye su identidad y convive democráticamente',
                'Interpreta la realidad social desde múltiples perspectivas',
                'Gestiona responsablemente los recursos económicos',
                'Participa en asuntos públicos para promover el bien común',
                'Valora la diversidad cultural y lingüística del Perú',
                'Desarrolla sentido de pertenencia a su comunidad y país',
            ],
            'Ciencia y Tecnología' => [
                'Indaga mediante métodos científicos para construir conocimientos',
                'Explica el mundo físico basándose en conocimientos científicos',
                'Diseña y construye soluciones tecnológicas para resolver problemas',
                'Comprende las relaciones entre la ciencia, la tecnología y la sociedad',
                'Argumenta científicamente sobre fenómenos naturales',
                'Toma decisiones informadas sobre salud y ambiente',
            ],
            'Arte y Cultura' => [
                'Aprecia de manera crítica manifestaciones artístico-culturales',
                'Crea proyectos desde los lenguajes artísticos',
                'Contextualiza las manifestaciones artístico-culturales',
                'Desarrolla procesos creativos individuales y colaborativos',
                'Expresa sus emociones e ideas a través del arte',
                'Valora el patrimonio cultural local, nacional y mundial',
            ],
            'Educación Física' => [
                'Se desenvuelve de manera autónoma a través de su motricidad',
                'Asume una vida saludable mediante la actividad física',
                'Interactúa a través de sus habilidades sociomotrices',
                'Construye su corporeidad y identidad motriz',
                'Practica actividades físicas para mejorar su calidad de vida',
                'Respeta las diferencias individuales en la práctica deportiva',
            ],
            'Educación Religiosa' => [
                'Construye su identidad como persona humana, amada por Dios',
                'Abstrae el mensaje de salvación en su vida cotidiana',
                'Asume la experiencia del encuentro personal y comunitario con Dios',
                'Valora y vive en armonía con todas las personas y la creación',
                'Dialoga desde su fe con otros credos y visiones',
                'Promueve la justicia y la solidaridad desde su fe',
            ],
            'Inglés' => [
                'Se comunica oralmente en inglés como lengua extranjera',
                'Lee diversos tipos de textos escritos en inglés',
                'Escribe diversos tipos de textos en inglés',
                'Reflexiona sobre la lengua inglesa y su aprendizaje',
                'Utiliza estrategias para comprender textos en inglés',
                'Intercambia información en situaciones comunicativas reales',
            ],
        ];

        return $competencias[$asignatura] ?? [
            'Desarrolla habilidades específicas de la asignatura',
            'Aplica conocimientos en situaciones contextualizadas',
            'Construye understanding desde los saberes propios del área',
            'Resuelve problemas propios del campo disciplinar',
            'Argumenta desde los conceptos fundamentales del área',
            'Integra saberes para comprender la realidad compleja',
        ];
    }
}