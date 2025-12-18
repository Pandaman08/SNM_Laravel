<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InstitucionEducativa;

class InstitucionEducativaSeeder extends Seeder
{
    public function run(): void
    {
        InstitucionEducativa::create([
            'codigo_modular' => '1234567',
            'user_id' => 1, // Asegúrate de que exista un usuario con ID 1
            'dre' => 'Dirección Regional de Educación de Lima Metropolitana',
            'ugel' => 'UGEL 01',
            'logo_ie' => null,
            'nombre_colegio' => 'Brunning',
            'codigo_local_ie' => '123456',
            'modalidad_ie' => 'Educación Básica Regular',
            'genero_ie' => 'Mixto',
            'turno_ie' => 'Mañana y Tarde',
            'direccion_ie' => 'Av. Principal 123',
            'departamento_ie' => 'La Libertad',
            'provincia_ie' => 'Trujillo',
            'distrito_ie' => 'Trujillo',
        ]);
    }
}