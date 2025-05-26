<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Secretaria>
 */
class SecretariaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory()->secretaria(),
            'fecha_contratacion' => $this->faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'area_responsabilidad' => $this->faker->randomElement(['matriculas', 'academico', 'financiero', 'general']),
            'jornada_laboral' => $this->faker->randomElement(['completa', 'parcial', 'turno_mañana', 'turno_tarde']),
        ];
    }
}
