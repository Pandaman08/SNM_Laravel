<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Enums\UserRole;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

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
            'email' => $this->faker->unique()->safeEmail(),
            'rol' => $this->faker->randomElement(UserRole::values()),
            'estado' => $this->faker->boolean(100),
            'password' => static::$password ??= Hash::make('password'),
            'photo' => $this->faker->optional()->imageUrl(200, 200, 'people'),
            'address' => $this->faker->address(),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'created_at' => $this->faker->dateTimeBetween('-2 years', 'now'),
            'updated_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function admin()
    {
        return $this->state([
            'rol' => UserRole::ADMIN->value,
        ]);
    }

    public function secretaria()
    {
        return $this->state([
            'rol' => UserRole::SECRETARIA->value,
        ]);
    }

    public function docente()
    {
        return $this->state([
            'rol' => UserRole::DOCENTE->value,
        ]);
    }

    public function estudiante()
    {
        return $this->state([
            'rol' => UserRole::ESTUDIANTE->value,
        ]);
    }
     public function tutor()
    {
        return $this->state([
            'rol' => UserRole::TUTOR->value,
        ]);
    }
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
