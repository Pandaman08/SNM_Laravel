<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Periodo;
use App\Models\Matricula;
use App\Enums\AsistenciaEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Log;

class AuxiliarController extends Controller
{
    /**
     * Panel principal del auxiliar
     */
    public function panel_auxiliar()
    {
        $user = Auth::user(); // ✅ Variable que necesita la vista
        Carbon::setLocale('es');
        $hoy = Carbon::now('America/Lima')->startOfDay();
        
        $periodoActual = Periodo::where('fecha_inicio', '<=', $hoy)
            ->where('fecha_fin', '>=', $hoy)
            ->first();
        
        $totalEstudiantes = Matricula::where('estado', 'activo')->count();
        $asistenciasHoy = Asistencia::whereDate('fecha', $hoy)->count();
        
        // Estadísticas del día con nombres que espera la vista
        $estudiantesPresentes = Asistencia::whereDate('fecha', $hoy)
            ->where('estado', AsistenciaEstado::PRESENTE)
            ->count();
        $estudiantesTarde = Asistencia::whereDate('fecha', $hoy)
            ->where('estado', AsistenciaEstado::TARDE)
            ->count();
        $estudiantesAusentes = Asistencia::whereDate('fecha', $hoy)
            ->where('estado', AsistenciaEstado::AUSENTE)
            ->count();
        $estudiantesSinRegistro = $totalEstudiantes - $asistenciasHoy;
        
        $ultimasAsistencias = Asistencia::with(['estudiante.persona', 'periodo'])
            ->whereDate('fecha', $hoy)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
        $horarioPromedio = DB::table('secciones')
            ->selectRaw('
                SEC_TO_TIME(ROUND(AVG(TIME_TO_SEC(hora_entrada)))) as hora_entrada_promedio,
                SEC_TO_TIME(ROUND(AVG(TIME_TO_SEC(hora_salida)))) as hora_salida_promedio
            ')
            ->first();
        
        return view('pages.admin.panels.auxiliar', compact(
            'user',
            'totalEstudiantes',
            'asistenciasHoy',
            'estudiantesPresentes',
            'estudiantesTarde',
            'estudiantesAusentes',
            'estudiantesSinRegistro',
            'ultimasAsistencias',
            'periodoActual',
            'horarioPromedio'
        ));
    }

    /**
     * Scanner QR para registro de asistencias
     */
    public function scanner()
    {
        Carbon::setLocale('es');
        
        $periodos = Periodo::orderBy('fecha_inicio', 'desc')->get();
        $periodoActual = Periodo::where('fecha_inicio', '<=', now())
            ->where('fecha_fin', '>=', now())
            ->first();
            
        return view('pages.admin.asistencia.scanner', compact('periodos', 'periodoActual'));
    }

    /**
     * Procesar escaneo de código QR
     */
    public function processScan(Request $request)
    {
        $request->validate([
            'qr_code' => 'required|string',
            'id_periodo' => 'required|exists:periodos,id_periodo',
            'tipo_registro' => 'required|in:entrada,salida'
        ]);
        
        try {
            DB::beginTransaction();
            
            // ✅ VALIDAR QUE NO SEA FIN DE SEMANA
            date_default_timezone_set('America/Lima');
            $now = new \DateTime('now', new \DateTimeZone('America/Lima'));
            $diaSemana = (int) $now->format('N'); // 1=Lunes, 7=Domingo
            
            if ($diaSemana >= 6) { // 6=Sábado, 7=Domingo
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No se pueden registrar asistencias los fines de semana'
                ], 400);
            }
            
            // Buscar estudiante con matrícula activa
            $estudiante = Estudiante::with(['persona', 'matriculas' => function($query) {
                $query->where('estado', 'activo')->with('seccion.grado');
            }])->where('qr_code', $request->qr_code)->first();
            
            if (!$estudiante || !$estudiante->matriculas->first()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Estudiante no encontrado o sin matrícula activa'
                ], 404);
            }
            
            $seccion = $estudiante->matriculas->first()->seccion;
            
            $fechaHoy = $now->format('Y-m-d');
            $horaActual = $now->format('H:i:s');
            
            $asistencia = Asistencia::where('codigo_estudiante', $estudiante->codigo_estudiante)
                ->where('id_periodo', $request->id_periodo)
                ->whereDate('fecha', $fechaHoy)
                ->first();
            
            if ($request->tipo_registro === 'entrada') {
                $resultado = $this->procesarEntrada($asistencia, $estudiante, $seccion, $fechaHoy, $horaActual, $request->id_periodo);
            } else {
                $resultado = $this->procesarSalida($asistencia, $horaActual);
            }
            
            if (!$resultado['success']) {
                DB::rollBack();
                return response()->json($resultado, $resultado['code'] ?? 400);
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $resultado['message'],
                'estudiante' => [
                    'codigo' => $estudiante->codigo_estudiante,
                    'nombres' => $estudiante->persona->name,
                    'apellidos' => $estudiante->persona->lastname,
                    'grado' => $seccion->grado->nombre_completo ?? 'N/A',
                    'seccion' => $seccion->seccion ?? 'N/A',
                    'estado' => $resultado['asistencia']->estado->value,
                    'hora_entrada' => $resultado['asistencia']->hora_entrada,
                    'hora_salida' => $resultado['asistencia']->hora_salida,
                    'observacion' => $resultado['asistencia']->observacion,
                    'horario_seccion' => [
                        'entrada' => $seccion->hora_entrada,
                        'salida' => $seccion->hora_salida
                    ]
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en processScan', [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesar registro de entrada
     */
    private function procesarEntrada($asistencia, $estudiante, $seccion, $fechaHoy, $horaActual, $periodoId)
    {
        if ($asistencia && $asistencia->hora_entrada) {
            return [
                'success' => false,
                'message' => 'Ya se registró la entrada hoy'
            ];
        }
        
        // Calcular tardanza
        $horaActualObj = \DateTime::createFromFormat('H:i:s', $horaActual);
        $horaEsperadaObj = \DateTime::createFromFormat('H:i:s', $seccion->hora_entrada);
        $minutosRetraso = (int) round(($horaActualObj->getTimestamp() - $horaEsperadaObj->getTimestamp()) / 60);
        
        // Determinar estado y observación
        [$estado, $observacion, $mensaje] = $this->calcularEstadoEntrada($minutosRetraso);
        
        $datosAsistencia = [
            'hora_entrada' => $horaActual,
            'estado' => $estado,
            'observacion' => $observacion,
            'tipo_registro' => 'entrada'
        ];
        
        if ($asistencia) {
            $asistencia->update($datosAsistencia);
        } else {
            $asistencia = Asistencia::create(array_merge($datosAsistencia, [
                'codigo_estudiante' => $estudiante->codigo_estudiante,
                'id_periodo' => $periodoId,
                'fecha' => $fechaHoy
            ]));
        }
        
        return [
            'success' => true,
            'message' => $mensaje,
            'asistencia' => $asistencia->fresh()
        ];
    }

    /**
     * Calcular estado de entrada según tardanza
     */
    private function calcularEstadoEntrada($minutosRetraso)
    {
        if ($minutosRetraso <= 0) {
            $observacion = $minutosRetraso < -15 
                ? "Llegó " . abs($minutosRetraso) . "min antes" 
                : "Entrada registrada correctamente";
            return [AsistenciaEstado::PRESENTE, $observacion, 'Entrada registrada'];
        }
        
        if ($minutosRetraso > 120) {
            $horas = floor($minutosRetraso / 60);
            $mins = $minutosRetraso % 60;
            $texto = $horas > 0 ? "{$horas}h {$mins}min" : "{$mins}min";
            return [
                AsistenciaEstado::AUSENTE,
                "Falta: Llegó {$texto} tarde",
                "Falta: {$texto} tarde"
            ];
        }
        
        // Tardanza (1-120 minutos)
        if ($minutosRetraso >= 60) {
            $horas = floor($minutosRetraso / 60);
            $mins = $minutosRetraso % 60;
            $texto = "{$horas}h {$mins}min";
        } else {
            $texto = "{$minutosRetraso}min";
        }
        
        return [
            AsistenciaEstado::TARDE,
            "Llegó {$texto} tarde",
            "Tardanza: {$texto}"
        ];
    }

    /**
     * Procesar registro de salida
     */
    private function procesarSalida($asistencia, $horaActual)
    {
        if (!$asistencia || !$asistencia->hora_entrada) {
            return [
                'success' => false,
                'message' => 'No se puede registrar salida sin entrada previa'
            ];
        }
        
        if ($asistencia->hora_salida) {
            return [
                'success' => false,
                'message' => 'Ya se registró la salida hoy'
            ];
        }
        
        $asistencia->update([
            'hora_salida' => $horaActual,
            'tipo_registro' => 'salida'
        ]);
        
        return [
            'success' => true,
            'message' => 'Salida registrada correctamente',
            'asistencia' => $asistencia->fresh()
        ];
    }

    /**
     * Listar justificaciones pendientes
     */
    public function justificacionesPendientes()
    {
        $justificacionesPendientes = Asistencia::with([
                'estudiante.persona',
                'estudiante.matriculas' => function($query) {
                    $query->where('estado', 'activo')->with('seccion.grado');
                },
                'periodo'
            ])
            ->where('estado', AsistenciaEstado::AUSENTE)
            ->whereNotNull('justificacion')
            ->where('estado_justificacion', 'pendiente')
            ->orderBy('fecha_solicitud_justificacion', 'desc')
            ->paginate(20);
        
        Log::info('Justificaciones pendientes cargadas', [
            'total' => $justificacionesPendientes->count()
        ]);
        
        return view('pages.admin.asistencia.auxiliar-justificaciones', compact('justificacionesPendientes'));
    }

    /**
     * Aprobar justificación
     */
    public function aprobarJustificacion($id)
    {
        $user = Auth::user();
        
        if (!$user->isAuxiliar() && !$user->isAdmin()) {
            Log::warning('Intento de aprobar sin permisos', [
                'user_id' => $user->user_id,
                'rol' => $user->rol->value
            ]);
            return back()->with('error', 'No tiene permisos para realizar esta acción');
        }
        
        $asistencia = Asistencia::findOrFail($id);
        
        if ($asistencia->estado_justificacion !== 'pendiente') {
            return back()->with('error', 'Esta justificación ya fue procesada');
        }
        
        $asistencia->update([
            'estado' => AsistenciaEstado::JUSTIFICADO,
            'estado_justificacion' => 'aprobada',
            'fecha_revision_justificacion' => now(),
            'revisado_por' => $user->user_id,
            'observacion' => 'Falta justificada: ' . substr($asistencia->justificacion, 0, 100)
        ]);
        
        Log::info('Justificación aprobada', [
            'asistencia_id' => $id,
            'auxiliar_id' => $user->user_id
        ]);
        
        return back()->with('success', 'Justificación aprobada. Estado cambió a "Justificado".');
    }

    /**
     * Rechazar justificación
     */
    public function rechazarJustificacion(Request $request, $id)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|max:500'
        ], [
            'motivo_rechazo.required' => 'Debe especificar el motivo del rechazo'
        ]);
        
        $user = Auth::user();
        
        if (!$user->isAuxiliar() && !$user->isAdmin()) {
            Log::warning('Intento de rechazar sin permisos', [
                'user_id' => $user->user_id,
                'rol' => $user->rol->value
            ]);
            return back()->with('error', 'No tiene permisos para realizar esta acción');
        }
        
        $asistencia = Asistencia::findOrFail($id);
        
        if ($asistencia->estado_justificacion !== 'pendiente') {
            return back()->with('error', 'Esta justificación ya fue procesada');
        }
        
        $asistencia->update([
            'estado_justificacion' => 'rechazada',
            'motivo_rechazo' => $request->motivo_rechazo,
            'fecha_revision_justificacion' => now(),
            'revisado_por' => $user->user_id
        ]);
        
        Log::info('Justificación rechazada', [
            'asistencia_id' => $id,
            'auxiliar_id' => $user->user_id
        ]);
        
        return back()->with('success', 'Justificación rechazada. El estudiante permanece como "Ausente".');
    }

    /**
     * Marcar ausencias manualmente (ejecutar comando)
     */
    public function marcarAusenciasManual()
    {
        try {
            Artisan::call('asistencia:marcar-ausencias');
            
            Log::info('Ausencias marcadas manualmente', [
                'user_id' => Auth::id()
            ]);
            
            return back()->with('success', 'Ausencias automáticas marcadas correctamente');
        } catch (\Exception $e) {
            Log::error('Error al marcar ausencias', ['error' => $e->getMessage()]);
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}