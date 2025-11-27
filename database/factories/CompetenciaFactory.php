<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Asignatura;

class CompetenciaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'codigo_asignatura' => Asignatura::factory(),
            'descripcion' => $this->faker->sentence(8),
        ];
    }

    public function forAsignatura(Asignatura $asignatura): static
    {
        return $this->state(fn (array $attributes) => [
            'codigo_asignatura' => $asignatura->codigo_asignatura,
        ]);
    }

    public function competenciaGeneral(): static
    {
        $competenciasGenerales = [
            'Se desenvuelve en los entornos virtuales generados por las TIC',
            'Gestiona su aprendizaje de manera autónoma',
            'Desarrolla procesos autónomos de aprendizaje en relación con los recursos existentes',
            'Interactúa a través de sus habilidades sociemocionales',
            'Participa en teams asumiendo diversos roles',
            'Construye y usa understanding matemático en situaciones problemáticas',
            'Resuelve problemas de cantidad, forma y gestión de datos',
            'Interpreta la realidad y toma decisiones desde una perspectiva matemática',
            'Se comunica oralmente en su lengua materna',
            'Lee diversos tipos de textos escritos en su lengua materna',
            'Escribe diversos tipos de textos en su lengua materna',
        ];

        return $this->state(fn (array $attributes) => [
            'descripcion' => $this->faker->randomElement($competenciasGenerales),
        ]);
    }

    public function competenciaEspecifica(string $asignatura): static
    {
        $competenciasPorAsignatura = [
            'Comunicación' => [
                'Se comunica para el desarrollo personal y la convivencia',
                'Comprende críticamente diversos tipos de textos orales',
                'Comprende críticamente diversos tipos de textos escritos',
                'Produce reflexivamente diversos tipos de textos escritos',
            ],
            'Matemática' => [
                'Resuelve problemas de regularidad, equivalencia y cambio',
                'Resuelve problemas de cantidad, forma y movimiento',
                'Resuelve problemas de gestión de datos e incertidumbre',
                'Modela objetos y situaciones con formas geométricas',
            ],
            'Personal Social' => [
                'Construye su identidad y convive democráticamente',
                'Interpreta la realidad social desde múltiples perspectivas',
                'Gestiona responsablemente los recursos económicos',
                'Participa en asuntos públicos para promover el bien común',
            ],
            'Ciencia y Tecnología' => [
                'Indaga mediante métodos científicos para construir conocimientos',
                'Explica el mundo físico basándose en conocimientos científicos',
                'Diseña y construye soluciones tecnológicas para resolver problemas',
                'Comprende las relaciones entre la ciencia, la tecnología y la sociedad',
            ],
            'Arte y Cultura' => [
                'Aprecia de manera crítica manifestaciones artístico-culturales',
                'Crea proyectos desde los lenguajes artísticos',
                'Contextualiza las manifestaciones artístico-culturales',
                'Desarrolla procesos creativos individuales y colaborativos',
            ],
            'Educación Física' => [
                'Se desenvuelve de manera autónoma a través de su motricidad',
                'Asume una vida saludable mediante la actividad física',
                'Interactúa a través de sus habilidades sociomotrices',
                'Construye su corporeidad y identidad motriz',
            ],
            'Educación Religiosa' => [
                'Construye su identidad como persona humana, amada por Dios',
                'Abstrae el mensaje de salvación en su vida cotidiana',
                'Asume la experiencia del encuentro personal y comunitario con Dios',
                'Valora y vive en armonía con todas las personas y la creación',
            ],
            'Inglés' => [
                'Se comunica oralmente en inglés como lengua extranjera',
                'Lee diversos tipos de textos escritos en inglés',
                'Escribe diversos tipos de textos en inglés',
                'Reflexiona sobre la lengua inglesa y su aprendizaje',
            ],
            'Historia, Geografía y Economía' => [
                'Construye interpretaciones históricas sobre procesos sociales',
                'Gestiona responsablemente el espacio y el ambiente',
                'Gestiona responsablemente los recursos económicos',
                'Interpreta críticamente fuentes diversas',
            ],
            'Formación Ciudadana y Cívica' => [
                'Construye su identidad como persona humana',
                'Ejerce su ciudadanía de manera plena y responsable',
                'Participa democráticamente en la búsqueda del bien común',
                'Delibera sobre asuntos públicos desde una perspectiva ética',
            ],
            'Biología' => [
                'Indaga mediante métodos científicos para construir conocimientos biológicos',
                'Explica el mundo vivo basándose en conocimientos sobre seres vivos',
                'Comprende las relaciones entre los seres vivos y su ambiente',
                'Toma decisiones informadas sobre salud y ambiente',
            ],
            'Física' => [
                'Indaga mediante métodos científicos para construir conocimientos físicos',
                'Explica el mundo físico basándose en conocimientos sobre materia y energía',
                'Aplica principios físicos en situaciones cotidianas',
                'Diseña soluciones tecnológicas aplicando principios físicos',
            ],
            'Química' => [
                'Indaga mediante métodos científicos para construir conocimientos químicos',
                'Explica la composición y transformación de la materia',
                'Comprende las reacciones químicas en contextos diversos',
                'Aplica principios de química en situaciones de la vida diaria',
            ],
        ];

        $competencias = $competenciasPorAsignatura[$asignatura] ?? [
            'Desarrolla habilidades específicas de la asignatura',
            'Aplica conocimientos en situaciones contextualizadas',
            'Construye understanding desde los saberes propios del área',
        ];

        return $this->state(fn (array $attributes) => [
            'descripcion' => $this->faker->randomElement($competencias),
        ]);
    }
}