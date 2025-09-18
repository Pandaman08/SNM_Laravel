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

    public function getLabel(): string
    {
        return match($this) {
            self::ADMIN => 'admin',
            self::SECRETARIA => 'secretaria',
            self::DOCENTE => 'docente',
            self::TUTOR => 'tutor',
            self::ESTUDIANTE => 'estudiante',
        };
    }

    public function getColor(): string
    {
        return match($this) {
            self::ADMIN => 'bg-[#2e5382] text-white',
            self::SECRETARIA => 'bg-purple-100 text-purple-800',
            self::DOCENTE => 'bg-blue-100 text-blue-800',
            self::TUTOR => 'bg-green-100 text-green-800',
            self::ESTUDIANTE => 'bg-yellow-100 text-yellow-800', // Added color for estudiante
        };
    }

    public function getIcon(): string
    {
        return match($this) {
            self::ADMIN => '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9.504 1.132a1 1 0 01.992 0l1.75 1a1 1 0 11-.992 1.736L10 3.152l-1.254.716a1 1 0 11-.992-1.736l1.75-1z" clip-rule="evenodd"></path></svg>',
            self::SECRETARIA => '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6z" clip-rule="evenodd"></path></svg>',
            self::DOCENTE => '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
            self::TUTOR => '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>',
            self::ESTUDIANTE => '<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path></svg>', // Added icon for estudiante
        };
    }

    public static function getStatisticsLabels(): array
    {
        return [
            self::ADMIN->value => 'Administradores',
            self::SECRETARIA->value => 'Secretarias',
            self::DOCENTE->value => 'Docentes',
            self::TUTOR->value => 'Tutores',
            self::ESTUDIANTE->value => 'Estudiantes', // Added label for estudiante
        ];
    }

    public function dashboardRoute(): string
    {
        return match($this) {
            self::ADMIN => 'home.admin',
            self::SECRETARIA => 'home.secretaria',
            self::DOCENTE => 'home.docente',
            self::TUTOR => 'home.tutor',
            self::ESTUDIANTE => 'home.estudiante', // Added route for estudiante
        };
    }
}