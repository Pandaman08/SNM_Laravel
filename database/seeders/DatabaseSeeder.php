<?php

namespace Database\Seeders;


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

        \App\Models\User::factory()
            ->admin()
            ->create([
                'name' => 'Admin',
                'email' => 'admin@school.com',
                'estado' => true
            ]);

        // Create 5 secretaries
        \App\Models\User::factory()
            ->secretaria()
            ->has(\App\Models\Secretaria::factory()->count(1))
            ->count(5)
            ->create();

        // Create 10 teachers
        \App\Models\User::factory()
            ->docente()
            ->has(\App\Models\Docente::factory()->count(1))
            ->count(10)
            ->create();

        // Create 20 students
        \App\Models\User::factory()
            ->tutor()
            ->has(\App\Models\Tutor::factory()->count(1))
            ->count(20)
            ->create();
    }
}
