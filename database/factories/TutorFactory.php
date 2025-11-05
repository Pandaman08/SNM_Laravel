<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tutor>
 */
class TutorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'user_id' => \App\Models\User::factory()->tutor(),
            'parentesco' => $this->faker->randomElement(['Padre', 'Madre']),
            'lugar_trabajo' => $this->faker->company(),
            'oficio' => $this->faker->jobTitle(),
        ];
    }
}
