<?php

namespace App\Enums;

enum AsistenciaEstado:String {
    case PRESNTE = 'Presente';
    case AUSENTE = 'Ausente';
    case JUSTIFICADO = 'Justificado';
    case TARDE = 'Tarde';

    public static function values(): array
    {
        return array_column(self::cases(), 'value'); 
    }

}
