<?php

namespace App\Enums;

enum AsistenciaEstado: String {
    case PRESENTE = 'Presente';
    case AUSENTE = 'Ausente';
    case JUSTIFICADO = 'Justificado';
    case TARDE = 'Tarde';

    public static function values(): array
    {
        return array_column(self::cases(), 'value'); 
    }

    public function getInitial(): string
    {
        return match($this) {
            self::PRESENTE => 'P',
            self::AUSENTE => 'A',
            self::JUSTIFICADO => 'J',
            self::TARDE => 'T',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::PRESENTE => 'green',
            self::AUSENTE => 'red',
            self::JUSTIFICADO => 'blue',
            self::TARDE => 'yellow',
        };
    }
}