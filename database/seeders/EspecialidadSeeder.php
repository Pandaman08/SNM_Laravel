<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Especialidad;
class EspecialidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $especialidades = [
            [
                'nombre' => 'Ciencias Exactas',
                'descripcion' => 'Enseñanza de matemáticas, física, química y áreas afines',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Humanidades',
                'descripcion' => 'Enseñanza de lengua, literatura, comunicación y filosofía',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Ciencias Sociales',
                'descripcion' => 'Enseñanza de historia, geografía, economía y formación cívica',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Ciencias Naturales',
                'descripcion' => 'Enseñanza de biología, ecología, ciencias de la tierra',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Idiomas',
                'descripcion' => 'Enseñanza de idiomas extranjeros como inglés y otros',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Educación Artística',
                'descripcion' => 'Enseñanza de arte, música, danza y expresión cultural',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Educación Física y Salud',
                'descripcion' => 'Enseñanza de deportes, educación física y promoción de la salud',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Tecnología e Informática',
                'descripcion' => 'Enseñanza de tecnología, computación y ciencias aplicadas',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Formación Ética y Valores',
                'descripcion' => 'Enseñanza de religión, ética, valores y desarrollo personal',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Psicología y Orientación',
                'descripcion' => 'Orientación estudiantil, desarrollo personal y apoyo psicológico',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Educación Primaria Generalista',
                'descripcion' => 'Docente capacitado para múltiples áreas en educación básica',
                'estado' => 'Activo'
            ],
            [
                'nombre' => 'Ciencias Integradas',
                'descripcion' => 'Enseñanza de ciencia y tecnología de forma interdisciplinaria',
                'estado' => 'Activo'
            ]
        ];

        foreach ($especialidades as $especialidad) {
            Especialidad::create($especialidad);
        }
    }
}
