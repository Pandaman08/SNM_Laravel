<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PeriodoFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->randomElement(['I', 'II', 'III', 'IV']),
            'fecha_inicio' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'fecha_fin' => $this->faker->dateTimeBetween('+2 months', '+4 months'),
            'estado' => 'Proceso',
        ];
    }
}