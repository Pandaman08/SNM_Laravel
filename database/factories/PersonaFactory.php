<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PersonaFactory extends Factory
{
    public function definition(): array
    {
        $dni = $this->faker->unique()->numberBetween(10000000, 99999999);
        $celular = '9' . $this->faker->unique()->numerify('########');

        return [
            'name' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'dni' => $dni,
            'phone' => $celular,
            'sexo' => $this->faker->randomElement(['M', 'F']),
            'estado_civil' => $this->faker->randomElement(['S', 'C', 'V', 'D']),
            'photo' => null,
            'address' => $this->faker->streetAddress() . ', ' . $this->faker->city(),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
        ];
    }
}