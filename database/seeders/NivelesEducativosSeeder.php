<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NivelEducativo;

class NivelesEducativosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $niveles = [
            [
                'nombre' => 'Inicial',
                'codigo' => 'INI',
                'descripcion' => 'Educación Inicial - Formación temprana y desarrollo integral',
                'edad_minima' => 3,
                'edad_maxima' => 5,
                'activo' => true
            ],
            [
                'nombre' => 'Primaria',
                'codigo' => 'PRI', 
                'descripcion' => 'Educación Primaria - Formación básica integral',
                'edad_minima' => 6,
                'edad_maxima' => 11,
                'activo' => true
            ],
            [
                'nombre' => 'Secundaria',
                'codigo' => 'SEC',
                'descripcion' => 'Educación Secundaria - Formación científica, humanística y técnica',
                'edad_minima' => 12,
                'edad_maxima' => 17,
                'activo' => true
            ]
        ];

        foreach ($niveles as $nivel) {
            NivelEducativo::create($nivel);
        }
    }
}