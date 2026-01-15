<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Asistencia;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Enums\AsistenciaEstado;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MarcarAusenciasAutomaticas extends Command
{
    protected $signature = 'asistencia:marcar-ausencias';
    protected $description = 'Marca automáticamente como ausentes a los estudiantes que no registraron entrada después de 2 horas';

    public function handle()
    {
        date_default_timezone_set('America/Lima');
        $now = new \DateTime('now', new \DateTimeZone('America/Lima'));
        $fechaHoy = $now->format('Y-m-d');
        $horaActual = $now->format('H:i:s');
        
        // ✅ VALIDACIÓN: NO EJECUTAR EN FIN DE SEMANA
        $diaSemana = (int) $now->format('N'); // 1=Lunes, 7=Domingo
        
        if ($diaSemana >= 6) { // 6=Sábado, 7=Domingo
            $nombreDia = $diaSemana === 6 ? 'Sábado' : 'Domingo';
            
            $this->warn("⚠️  Hoy es {$nombreDia}. No se marcan ausencias en fin de semana.");
            $this->info("📅 Las ausencias solo se registran de lunes a viernes.");
            
            Log::info('Comando de ausencias automáticas NO ejecutado', [
                'fecha' => $fechaHoy,
                'dia' => $nombreDia,
                'motivo' => 'Fin de semana'
            ]);
            
            return Command::SUCCESS;
        }
        
        // Obtener periodo actual
        $hoy = Carbon::now('America/Lima');
        $periodoActual = Periodo::where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->first();
        
        if (!$periodoActual) {
            $this->error('❌ No hay período activo para la fecha actual');
            
            Log::warning('No se marcaron ausencias: sin periodo activo', [
                'fecha' => $fechaHoy
            ]);
            
            return Command::FAILURE;
        }
        
        $this->info("📅 Marcando ausencias para: {$hoy->translatedFormat('l, d \d\e F \d\e Y')}");
        $this->info("📖 Período: {$periodoActual->nombre}");
        $this->info("⏰ Hora actual: {$horaActual}");
        
        // Obtener todas las matrículas activas con su sección
        $matriculas = Matricula::with(['estudiante.persona', 'seccion'])
            ->where('estado', 'activo')
            ->get();
        
        $totalEstudiantes = $matriculas->count();
        $this->info("👥 Total de estudiantes activos: {$totalEstudiantes}");
        
        // Crear barra de progreso
        $bar = $this->output->createProgressBar($totalEstudiantes);
        $bar->start();
        
        $ausenciasCreadas = 0;
        $yaConRegistro = 0;
        $sinSeccion = 0;
        $noAlcanzaronLimite = 0;
        
        foreach ($matriculas as $matricula) {
            $estudiante = $matricula->estudiante;
            $seccion = $matricula->seccion;
            
            // ✅ DEBUG: Mostrar qué estudiante se está procesando
            $this->newLine();
            $this->info("👤 Procesando: {$estudiante->persona->name} {$estudiante->persona->lastname}");
            
            if (!$seccion || !$seccion->hora_entrada) {
                $this->warn("   ⚠️  Sin sección o sin hora de entrada configurada");
                $sinSeccion++;
                $bar->advance();
                continue;
            }
            
            $this->info("   📍 Sección: {$seccion->seccion} | Entrada: {$seccion->hora_entrada}");
            
            // Verificar si ya tiene asistencia registrada hoy
            $asistenciaExistente = Asistencia::where('codigo_estudiante', $estudiante->codigo_estudiante)
                ->where('id_periodo', $periodoActual->id_periodo)
                ->whereDate('fecha', $fechaHoy)
                ->first();
            
            if ($asistenciaExistente) {
                $this->info("   ✅ Ya tiene registro hoy: {$asistenciaExistente->estado->value}");
                $yaConRegistro++;
                $bar->advance();
                continue;
            }
            
            // ✅ CORRECCIÓN: Comparar fechas completas del mismo día
            // Crear DateTime con fecha completa para comparación correcta
            $fechaHoraEntrada = \DateTime::createFromFormat('Y-m-d H:i:s', $fechaHoy . ' ' . $seccion->hora_entrada, new \DateTimeZone('America/Lima'));
            $fechaHoraLimite = clone $fechaHoraEntrada;
            $fechaHoraLimite->modify('+2 hours');
            
            $fechaHoraActual = \DateTime::createFromFormat('Y-m-d H:i:s', $fechaHoy . ' ' . $horaActual, new \DateTimeZone('America/Lima'));
            
            $this->info("   🕐 Límite entrada + 2h: {$fechaHoraLimite->format('Y-m-d H:i:s')}");
            $this->info("   ⏰ Hora actual: {$fechaHoraActual->format('Y-m-d H:i:s')}");
            
            // Si ya pasaron más de 2 horas desde la entrada esperada
            if ($fechaHoraActual >= $fechaHoraLimite) {
                $this->info("   ✅ SÍ pasó el límite - Marcando ausencia...");
                try {
                    // ✅ CORRECCIÓN: tipo_registro = 'entrada' (valor válido en la BD)
                    Asistencia::create([
                        'codigo_estudiante' => $estudiante->codigo_estudiante,
                        'id_periodo' => $periodoActual->id_periodo,
                        'fecha' => $fechaHoy,
                        'estado' => AsistenciaEstado::AUSENTE,
                        'observacion' => 'Ausencia automática: No registró entrada',
                        'tipo_registro' => 'entrada', // ✅ Usar 'entrada' en lugar de 'automatico'
                        'hora_entrada' => null,
                        'hora_salida' => null
                    ]);
                    
                    $this->info("   ✔️  Ausencia creada exitosamente");
                    $ausenciasCreadas++;
                    
                } catch (\Exception $e) {
                    $this->error("   ❌ Error al crear ausencia: {$e->getMessage()}");
                    
                    Log::error('Error al marcar ausencia automática', [
                        'codigo_estudiante' => $estudiante->codigo_estudiante,
                        'error' => $e->getMessage()
                    ]);
                }
            } else {
                $this->info("   ⏳ Aún no alcanza el límite de 2h");
                $noAlcanzaronLimite++;
            }
            
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine(2);

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✅ Proceso completado exitosamente");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->table(
            ['Concepto', 'Cantidad'],
            [
                ['Total estudiantes', $totalEstudiantes],
                ['Ya con registro', $yaConRegistro],
                ['Ausencias creadas', $ausenciasCreadas],
                ['Aún no alcanzan límite 2h', $noAlcanzaronLimite],
                ['Sin sección asignada', $sinSeccion],
            ]
        );
        
        Log::info('Ausencias automáticas marcadas exitosamente', [
            'fecha' => $fechaHoy,
            'periodo' => $periodoActual->nombre,
            'total_estudiantes' => $totalEstudiantes,
            'con_registro' => $yaConRegistro,
            'ausencias_creadas' => $ausenciasCreadas,
            'no_alcanzaron_limite' => $noAlcanzaronLimite,
            'sin_seccion' => $sinSeccion
        ]);
        
        return Command::SUCCESS;
    }
}