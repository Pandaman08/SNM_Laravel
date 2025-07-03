<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'dni' =>$this->faker->numberBetween(0, 99999999),
            'phone' => $this->faker->numberBetween(0, 999999999),
            'sexo' => 'M',
            'estado_civil' => 'C',
            'photo' => null,
            'address' => $this->faker->address(),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
             'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
