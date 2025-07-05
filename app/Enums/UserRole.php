<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case SECRETARIA = 'secretaria';
    case DOCENTE = 'docente';
    case ESTUDIANTE = 'estudiante';

    case TUTOR = 'tutor';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function dashboardRoute(): string
    {
        return match($this) {
            self::ADMIN => 'home.admin',
            self::SECRETARIA => 'home.secretaria',
            self::DOCENTE => 'home.docente',
            self::TUTOR => 'home.tutor',
        };
    }
}