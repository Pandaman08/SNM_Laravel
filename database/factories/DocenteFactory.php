<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\NivelEducativo;

class DocenteFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => null,
            'nivel_educativo_id' => NivelEducativo::inRandomOrder()->first()?->id_nivel_educativo,
            'firma_docente' => null,
        ];
    }

    public function primaria()
    {
        return $this->state(function () {
            return [
                'nivel_educativo_id' => NivelEducativo::where('nombre', 'Primaria')->first()?->id_nivel_educativo,
            ];
        });
    }

    public function secundaria()
    {
        return $this->state(function () {
            return [
                'nivel_educativo_id' => NivelEducativo::where('nombre', 'Secundaria')->first()?->id_nivel_educativo,
            ];
        });
    }
}