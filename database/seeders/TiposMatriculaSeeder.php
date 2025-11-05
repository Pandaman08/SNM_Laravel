<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TiposMatriculaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tipos_matricula')->insert([
            [
                'id_tipo_matricula' => 1,
                'nombre' => 'Matrícula de Ingreso',
                'descripcion' => 'Para estudiantes nuevos',
            ],
            [
                'id_tipo_matricula' => 2,
                'nombre' => 'Matrícula Regular',
                'descripcion' => 'Para continuidad de estudios',
            ],
        ]);
    }
}
