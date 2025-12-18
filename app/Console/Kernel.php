<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Ejecutar cada 30 minutos entre las 7:00 AM y 12:00 PM
        // ✅ SOLO DE LUNES A VIERNES
        $schedule->command('asistencia:marcar-ausencias')
            ->everyThirtyMinutes()
            ->between('7:00', '12:00')
            ->weekdays()  // ← ✅ AGREGAR ESTA LÍNEA
            ->timezone('America/Lima');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}