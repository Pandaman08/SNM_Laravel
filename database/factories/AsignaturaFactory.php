<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Grado;

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
    
    private static $asignaturasCreadas = [];

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
            $gradoId = $grado->id_grado;

            // Crear una clave única para este grado
            $claveGrado = "{$nivel}_{$gradoId}";

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

                // Obtener la siguiente asignatura no utilizada para este grado
                if (!isset(self::$asignaturasCreadas[$claveGrado])) {
                    self::$asignaturasCreadas[$claveGrado] = 0;
                }

                $indice = self::$asignaturasCreadas[$claveGrado] % count($asignaturasPrimaria);
                $asignatura->nombre = $asignaturasPrimaria[$indice];
                self::$asignaturasCreadas[$claveGrado]++;

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

                // Obtener la siguiente asignatura no utilizada para este grado
                if (!isset(self::$asignaturasCreadas[$claveGrado])) {
                    self::$asignaturasCreadas[$claveGrado] = 0;
                }

                $indice = self::$asignaturasCreadas[$claveGrado] % count($asignaturasSecundaria);
                $asignatura->nombre = $asignaturasSecundaria[$indice];
                self::$asignaturasCreadas[$claveGrado]++;
            }
        });
    }
}