<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class InstitucionEducativaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'codigo_modular' => $this->faker->unique()->numerify('######'),
            'user_id' => 1, 
            'dre' => 'Dirección Regional de Educación de Lima Metropolitana',
            'ugel' => 'UGEL 01',
            'logo_ie' => null,
            'nombre_colegio' => 'Brunning',
            'codigo_local_ie' => $this->faker->numerify('######'),
            'modalidad_ie' => 'Educación Básica Regular',
            'genero_ie' => 'Mixto',
            'turno_ie' => 'Mañana y Tarde',
            'direccion_ie' => 'Av. Principal 123',
            'departamento_ie' => 'La Libertad',
            'provincia_ie' => 'Trujillo',
            'distrito_ie' => 'Trujillo',
        ];
    }
}