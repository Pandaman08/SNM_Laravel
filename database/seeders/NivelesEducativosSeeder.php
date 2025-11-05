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
                'nombre' => 'Primaria',
                'estado' => true
            ],
            [
                'nombre' => 'Secundaria',
                'estado' => true
            ]
        ];

        foreach ($niveles as $nivel) {
            NivelEducativo::create($nivel);
        }
    }
}