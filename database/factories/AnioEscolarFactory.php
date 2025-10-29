<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AnioEscolarFactory extends Factory
{
    public function definition(): array
    {
        $anio = $this->faker->numberBetween(2024, 2025);
        
        return [
            'anio' => $anio,
            'descripcion' => "Año Escolar {$anio}",
            'fecha_inicio' => "{$anio}-03-01",
            'fecha_fin' => "{$anio}-12-20",
            'estado' => 'Activo'
        ];
    }
}