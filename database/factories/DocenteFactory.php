<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Docente>
 */
class DocenteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $especialidades = [
            'Matemáticas',
            'Lenguaje',
            'Ciencias',
            'Historia',
            'Inglés',
            'Educación Física',
            'Arte',
            'Música'
        ];

        return [
            'user_id' => \App\Models\User::factory()->docente(),
            'especialidad' => $this->faker->randomElement($especialidades),
            'jornada_laboral' => $this->faker->randomElement(['4343', '444', '45456', '123']),
            'fecha_contratacion' => $this->faker->dateTimeBetween('-10 years', 'now')->format('Y-m-d'),
            'departamento_estudios' => $this->faker->randomElement(['matematicas', 'ciencias', 'fisica', 'quimica']),  
        ];
    }
}
