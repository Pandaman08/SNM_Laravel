<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\Hash;

use App\Enums\UserRole;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $adminPersona = \App\Models\Persona::factory()->create([
            'name' => 'Admin',
            'lastname' => 'System',
            'dni' => '00000001',
            'phone' => '924996999',
            'sexo' => 'M',
            'estado_civil' => 'C'
        ]);

        \App\Models\User::create([
            'persona_id' => $adminPersona->persona_id,
            'email' => 'admin@school.com',
            'rol' => UserRole::ADMIN->value,
            'estado' => true,
            'password' => Hash::make('password')
        ]);

        // Secretarias (5)
        \App\Models\User::factory()
            ->secretaria()
            ->has(\App\Models\Secretaria::factory())
            ->count(5)
            ->create();

        // Docentes (10)
        \App\Models\User::factory()
            ->docente()
            ->has(\App\Models\Docente::factory())
            ->count(10)
            ->create();

        // Tutores (20)
        \App\Models\User::factory()
            ->tutor()
            ->has(\App\Models\Tutor::factory())
            ->count(20)
            ->create();

        $this->call([
            NivelesEducativosSeeder::class,  // 1° Crear niveles educativos
            GradosSeeder::class,      // 2° Crear grados para tu estructura
            SeccionesSeeder::class,          // 3° Crear secciones
        ]);
    
    }
}
