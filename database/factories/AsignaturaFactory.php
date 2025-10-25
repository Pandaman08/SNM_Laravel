<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Grado;
use App\Models\NivelEducativo;

class AsignaturaFactory extends Factory
{

    private const COMUNICACION = 'Comunicación';
    private const MATEMATICA = 'Matemática';
    private const PERSONAL_SOCIAL = 'Personal Social';
    private const CIENCIA_TECNOLOGIA = 'Ciencia y Tecnología';
    private const ARTE_CULTURA = 'Arte y Cultura';
    private const EDUCACION_FISICA = 'Educación Física';
    private const EDUCACION_RELIGIOSA = 'Educación Religiosa';
    private const INGLES = 'Inglés';
    
    public function definition()
    {
        $gradoId = Grado::inRandomOrder()->first()?->id_grado;

        return [
            'id_grado' => $gradoId,
            'nombre' => 'Placeholder',
        ];
    }

    public function configure()
    {
        return $this->afterMaking(function ($asignatura) {
            $grado = $asignatura->grado ?? Grado::find($asignatura->id_grado);
            if (!$grado) return;

            $nivel = $grado->nivelEducativo->nombre ?? null;
            $gradoNumero = $grado->grado;

            if ($nivel === 'Primaria') {
                $asignaturasPrimaria = [
                    self::COMUNICACION,
                    self::MATEMATICA,
                    self::PERSONAL_SOCIAL,
                    self::CIENCIA_TECNOLOGIA,
                    self::ARTE_CULTURA,
                    self::EDUCACION_FISICA,
                    self::EDUCACION_RELIGIOSA,
                    self::INGLES
                ];
                $asignatura->nombre = $this->faker->randomElement($asignaturasPrimaria);
            } elseif ($nivel === 'Secundaria') {
                $asignaturasSecundaria = match (true) {
                    in_array($gradoNumero, [1, 2]) => [
                        self::COMUNICACION,
                        self::MATEMATICA,
                        self::CIENCIA_TECNOLOGIA,
                        'Historia, Geografía y Economía',
                        'Formación Ciudadana y Cívica',
                        'Arte',
                        self::EDUCACION_FISICA,
                        self::EDUCACION_RELIGIOSA,
                        self::INGLES
                    ],
                    in_array($gradoNumero, [3, 4, 5]) => [
                        self::COMUNICACION,
                        self::MATEMATICA,
                        'Biología',
                        'Física',
                        'Química',
                        'Historia del Perú',
                        'Historia Universal',
                        'Geografía',
                        'Economía',
                        'Filosofía',
                        'Psicología',
                        'Cívica',
                        'Arte',
                        self::EDUCACION_FISICA,
                        self::EDUCACION_RELIGIOSA,
                        self::INGLES
                    ],
                    default => ['Comunicación']
                };
                $asignatura->nombre = $this->faker->randomElement($asignaturasSecundaria);
            }
        });
    }
}