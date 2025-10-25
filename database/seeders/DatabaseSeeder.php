<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $adminPersona = \App\Models\Persona::factory()->create([
            'name' => 'Cesar',
            'lastname' => 'Bruning',
            'dni' => '00000001',
            'phone' => '924996999',
            'sexo' => 'M',
            'estado_civil' => 'C',
            'photo' => null,
            'address' => 'Av.Tupac Amaru 547',
            'fecha_nacimiento' => '1985-01-15',
        ]);

        \App\Models\User::create([
            'persona_id' => $adminPersona->persona_id,
            'email' => 'admin@bruning.com',
            'rol' => UserRole::ADMIN->value,
            'estado' => true,
            'password' => Hash::make('password')
        ]);

        $auxiliarPersona = \App\Models\Persona::factory()->create([
            'name' => 'Maria', 
            'lastname' => 'Flores', 
            'dni' => '00000002',
            'phone' => '924996998',
            'sexo' => 'F',
            'estado_civil' => 'S',
            'photo' => null,
            'address' => 'Av.Juan Pablo II 456',
            'fecha_nacimiento' => '1990-01-01',
        ]);

        \App\Models\User::create([
            'persona_id' => $auxiliarPersona->persona_id,
            'email' => 'auxiliar@bruning.com',
            'rol' => UserRole::AUXILIAR->value,
            'estado' => true,
            'password' => Hash::make('password')
        ]);

        $secretarioPersona = \App\Models\Persona::factory()->create([
            'name' => 'Tulio',
            'lastname' => 'Triviño',
            'dni' => '74589631',
            'phone' => '924996997',
            'sexo' => 'M',
            'estado_civil' => 'C',
            'photo' => null,
            'address' => 'Av. Siempre Viva 123',
            'fecha_nacimiento' => '1980-05-15',
        ]);

        \App\Models\User::create([
            'persona_id' => $secretarioPersona->persona_id,
            'email' => 'secretario@bruning.com',
            'rol' => UserRole::SECRETARIA->value,
            'estado' => true,
            'password' => Hash::make('password')
        ]);

        // 1. Crear datos maestros
        $this->call([
            NivelesEducativosSeeder::class,
            GradosSeeder::class,
            SeccionesSeeder::class,
            TiposMatriculaSeeder::class,
        ]);

        // 2. Crear docentes (requiere niveles ya creados)
        \App\Models\User::factory()
            ->docente()
            ->has(\App\Models\Docente::factory()->primaria())
            ->count(18)
            ->create();

        \App\Models\User::factory()
            ->docente()
            ->has(\App\Models\Docente::factory()->secundaria())
            ->count(12)
            ->create();

        // 3. Crear tutor
        \App\Models\User::factory()
            ->tutor()
            ->has(\App\Models\Tutor::factory())
            ->count(1)
            ->create();

        // 4. Asignar asignaturas y secciones a docentes
        $this->call([
            AsignaturaSeeder::class,
            SeccionDocenteSeeder::class,
        ]);
    }
}