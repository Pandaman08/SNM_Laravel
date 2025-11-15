<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\Matricula;
use App\Models\Periodo;
use App\Enums\AsistenciaEstado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Log;

class AsistenciaController extends Controller
{
    // ==================== MÉTODOS PRINCIPALES ====================
    
    /**
     * Listar asistencias (Docente)
     */
    public function index(Request $request)
    {
        $docente = Auth::user()->docente;
        
        if (!$docente || $docente->secciones->isEmpty()) {
            return $this->errorView('asistencia.index', 'No tiene secciones asignadas');
        }

        $grados = Grado::whereIn('id_grado', $docente->secciones->pluck('id_grado'))->get();
        
        // ✅ OBTENER VALORES DE LOS FILTROS
        $gradoId = $request->get('grado_id');
        $seccionId = $request->get('seccion_id');
        $search = $request->get('search');
        $orderBy = $request->get('order_by', 'nombre');
        
        $secciones = $gradoId 
            ? $docente->secciones->where('id_grado', $gradoId) 
            : collect();

        $matriculas = $this->obtenerMatriculas($docente, $request);
        $periodos = Periodo::where('fecha_inicio', '<=', now())->orderBy('fecha_inicio')->get();
        $asistencias = $this->obtenerAsistencias($matriculas, $periodos);

        return view('pages.admin.asistencia.index', compact(
            'matriculas',
            'grados', 
            'secciones', 
            'periodos', 
            'asistencias',
            'gradoId',      // ✅ Agregado
            'seccionId',    // ✅ Agregado
            'search',       // ✅ Agregado
            'orderBy'       // ✅ Agregado
        ));
    }

    /**
     * Crear nueva asistencia (Docente)
     */
    public function create(Request $request)
    {
        $docente = Auth::user()->docente;
        
        if (!$docente || $docente->secciones->isEmpty()) {
            return $this->errorView('asistencia.create', 'No tiene secciones asignadas');
        }

        $fecha = $request->get('fecha', now()->format('Y-m-d'));
        $periodo = Periodo::where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha)
            ->first();

        $grados = Grado::whereIn('id_grado', $docente->secciones->pluck('id_grado'))->get();
        
        // ✅ OBTENER VALORES DE LOS FILTROS
        $gradoId = $request->get('grado_id');
        $seccionId = $request->get('seccion_id');
        $search = $request->get('search');
        
        $secciones = $gradoId 
            ? $docente->secciones->where('id_grado', $gradoId) 
            : collect();

        $matriculas = collect();
        $asistenciasExistentes = [];

        if ($gradoId && $seccionId && $periodo) {
            $matriculas = $this->obtenerMatriculasSeccion($seccionId, $search);
            $asistenciasExistentes = Asistencia::whereIn('codigo_estudiante', $matriculas->pluck('codigo_estudiante'))
                ->where('fecha', $fecha)
                ->where('id_periodo', $periodo->id_periodo)
                ->pluck('codigo_estudiante')
                ->toArray();
        }

        $mensajePeriodo = !$periodo ? 'No hay periodo activo para esta fecha' : null;
        
        $periodos = Periodo::orderBy('fecha_inicio', 'desc')->get();

        return view('pages.admin.asistencia.create', compact(
            'matriculas',
            'grados',
            'secciones',
            'periodo',
            'periodos',
            'asistenciasExistentes',
            'mensajePeriodo',
            'fecha',
            'gradoId',      // ✅ Agregado
            'seccionId',    // ✅ Agregado
            'search'        // ✅ Agregado
        ));
    }

    /**
     * Guardar asistencias
     */
    public function store(Request $request)
    {
        $fecha = $request->input('fecha');
        $periodo = Periodo::findOrFail($request->input('id_periodo'));

        // ✅ VALIDAR QUE NO SEA FIN DE SEMANA
        $fechaCarbon = Carbon::parse($fecha);
        if ($fechaCarbon->isWeekend()) {
            return back()
                ->withInput()
                ->with('error', 'No se pueden registrar asistencias en sábado ni domingo');
        }

        // ✅ VALIDAR QUE LA FECHA ESTÉ DENTRO DEL PERIODO
        if ($fechaCarbon->notBetween($periodo->fecha_inicio, $periodo->fecha_fin)) {
            return back()
                ->withInput()
                ->with('error', 'La fecha está fuera del rango del periodo seleccionado');
        }

        foreach ($request->asistencias as $asis) {
            Asistencia::firstOrCreate(
                [
                    'codigo_estudiante' => $asis['codigo_estudiante'],
                    'fecha' => $fecha,
                    'id_periodo' => $periodo->id_periodo
                ],
                [
                    'estado' => $asis['estado'],
                    'observacion' => $asis['observacion'] ?? null
                ]
            );
        }

        return redirect()->route('asistencias.index')->with('success', 'Asistencias registradas correctamente');
    }

    /**
     * Ver asistencias de un estudiante (con calendario)
     */
    public function showAsistenciasEstudiante($codigo_estudiante)
    {
        $matricula = Matricula::with('estudiante.persona', 'seccion.grado')
            ->where('codigo_estudiante', $codigo_estudiante)
            ->firstOrFail();

        $periodos = Periodo::orderBy('fecha_inicio')->get();
        $asistencias = Asistencia::where('codigo_estudiante', $codigo_estudiante)
            ->with('periodo')
            ->get();

        // Convertir a formato para JavaScript
        $asistenciasPlanas = $asistencias->mapWithKeys(function($asis) {
            return [
                Carbon::parse($asis->fecha)->format('Y-m-d') => [
                    'id_asistencia' => $asis->id_asistencia,
                    'fecha' => Carbon::parse($asis->fecha)->format('Y-m-d'),
                    'estado' => $asis->estado->value,
                    'observacion' => $asis->observacion,
                    'justificacion' => $asis->justificacion,
                    'estado_justificacion' => $asis->estado_justificacion,
                    'motivo_rechazo' => $asis->motivo_rechazo,
                    'fecha_solicitud_justificacion' => $asis->fecha_solicitud_justificacion?->format('Y-m-d H:i:s'),
                    'archivo_justificacion' => $asis->archivo_justificacion,
                    'archivo_justificacion_original' => $asis->archivo_justificacion_original,
                    'periodo' => $asis->periodo ? [
                        'id_periodo' => $asis->periodo->id_periodo,
                        'nombre' => $asis->periodo->nombre,
                        'fecha_inicio' => $asis->periodo->fecha_inicio,
                        'fecha_fin' => $asis->periodo->fecha_fin,
                    ] : null
                ]
            ];
        });

        $statsPorPeriodo = $this->calcularEstadisticas($asistencias, $periodos);

        return view('pages.admin.asistencia.show-estudiante', compact(
            'matricula', 'statsPorPeriodo', 'asistenciasPlanas'
        ));
    }

    // ==================== JUSTIFICACIONES ====================

    /**
     * Solicitar justificación (Tutor)
     */
    public function solicitarJustificacion(Request $request)
    {
        $request->validate([
            'asistencia_id' => 'required|exists:asistencias,id_asistencia',
            'motivo_justificacion' => 'required|string|max:500',
            'archivo_justificacion' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120'
        ]);

        $user = Auth::user();
        
        if (!$user->tutor) {
            return back()->with('error', 'No tiene permisos para realizar esta acción');
        }

        $asistencia = Asistencia::findOrFail($request->asistencia_id);
        $estudiante = Estudiante::with('tutores')
            ->where('codigo_estudiante', $asistencia->codigo_estudiante)
            ->firstOrFail();

        // Validar autorización
        if (!$estudiante->tutores->contains('id_tutor', $user->tutor->id_tutor) 
            && $estudiante->tutores->isNotEmpty()) {
            return back()->with('error', 'No está autorizado para justificar este estudiante');
        }

        // Validar estado
        if ($asistencia->estado->value !== 'Ausente') {
            return back()->with('error', 'Solo se pueden justificar ausencias');
        }

        if ($asistencia->estado_justificacion && $asistencia->estado_justificacion !== 'rechazada') {
            return back()->with('error', 'Ya tiene una justificación en proceso');
        }

        DB::beginTransaction();
        try {
            // Eliminar archivo anterior si existe
            if ($asistencia->archivo_justificacion) {
                Storage::disk('public')->delete($asistencia->archivo_justificacion);
            }

            // Guardar nuevo archivo
            $file = $request->file('archivo_justificacion');
            $nombreArchivo = sprintf(
                'justificacion_%s_%s_%s.%s',
                $asistencia->codigo_estudiante,
                Carbon::parse($asistencia->fecha)->format('Ymd'),
                time(),
                $file->getClientOriginalExtension()
            );
            
            $archivoPath = $file->storeAs('justificaciones', $nombreArchivo, 'public');

            // Actualizar asistencia
            $asistencia->update([
                'justificacion' => $request->motivo_justificacion,
                'archivo_justificacion' => $archivoPath,
                'archivo_justificacion_original' => $file->getClientOriginalName(),
                'estado_justificacion' => 'pendiente',
                'fecha_solicitud_justificacion' => now(),
                'motivo_rechazo' => null
            ]);

            DB::commit();
            
            Log::info('✅ Justificación enviada', ['asistencia_id' => $asistencia->id_asistencia]);
            
            return back()->with('success', 'Solicitud enviada correctamente');
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (isset($archivoPath) && Storage::disk('public')->exists($archivoPath)) {
                Storage::disk('public')->delete($archivoPath);
            }
            
            Log::error('❌ Error al guardar justificación', [
                'error' => $e->getMessage(),
                'line' => $e->getLine()
            ]);
            
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Descargar documento de justificación
     */
    public function descargarJustificacion($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $user = Auth::user();
        
        // Verificar permisos
        $puedeDescargar = $user->isAuxiliar() || $user->isAdmin();
        
        if (!$puedeDescargar && $user->tutor) {
            $estudiante = Estudiante::with('tutores')
                ->where('codigo_estudiante', $asistencia->codigo_estudiante)
                ->first();
            
            if ($estudiante && (
                $estudiante->tutores->contains('id_tutor', $user->tutor->id_tutor) || 
                $estudiante->tutores->isEmpty()
            )) {
                $puedeDescargar = true;
            }
        }
        
        if (!$puedeDescargar) {
            Log::warning('❌ Intento de descarga sin permisos', [
                'user_id' => $user->user_id,
                'asistencia_id' => $id
            ]);
            abort(403, 'No tiene permisos para descargar este archivo');
        }
        
        if (!$asistencia->archivo_justificacion || !Storage::disk('public')->exists($asistencia->archivo_justificacion)) {
            abort(404, 'Archivo no encontrado');
        }
        
        return Storage::disk('public')->download(
            $asistencia->archivo_justificacion,
            $asistencia->archivo_justificacion_original ?? 'justificacion.pdf'
        );
    }

    /**
     * Cancelar justificación pendiente (Tutor)
     */
    public function cancelarJustificacion($id)
    {
        $asistencia = Asistencia::findOrFail($id);
        $user = Auth::user();
        
        if (!$user->tutor) {
            return back()->with('error', 'No tiene permisos');
        }
        
        $estudiante = Estudiante::with('tutores')
            ->where('codigo_estudiante', $asistencia->codigo_estudiante)
            ->first();
        
        if ($estudiante && !$estudiante->tutores->contains('id_tutor', $user->tutor->id_tutor) 
            && $estudiante->tutores->isNotEmpty()) {
            return back()->with('error', 'No puede cancelar esta justificación');
        }
        
        if ($asistencia->estado_justificacion !== 'pendiente') {
            return back()->with('error', 'Solo se pueden cancelar justificaciones pendientes');
        }
        
        // Eliminar archivo
        if ($asistencia->archivo_justificacion) {
            Storage::disk('public')->delete($asistencia->archivo_justificacion);
        }
        
        $asistencia->update([
            'justificacion' => null,
            'archivo_justificacion' => null,
            'archivo_justificacion_original' => null,
            'estado_justificacion' => null,
            'fecha_solicitud_justificacion' => null
        ]);
        
        return back()->with('success', 'Justificación cancelada');
    }

    // ==================== MÉTODOS AUXILIARES ====================

    /**
     * Obtener matrículas con filtros
     */
    private function obtenerMatriculas($docente, $request)
    {
        $query = Matricula::with(['estudiante.persona', 'seccion.grado'])
            ->where('estado', true)
            ->whereIn('seccion_id', $docente->secciones->pluck('id_seccion'));

        if ($request->grado_id) {
            $query->whereHas('seccion', fn($q) => $q->where('id_grado', $request->grado_id));
        }

        if ($request->seccion_id) {
            $query->where('seccion_id', $request->seccion_id);
        }

        if ($request->search) {
            $query->whereHas('estudiante.persona', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('lastname', 'LIKE', "%{$request->search}%");
            });
        }

        $orderBy = $request->get('order_by', 'nombre') === 'apellido' ? 'lastname' : 'name';
        $query->join('estudiantes', 'matriculas.codigo_estudiante', '=', 'estudiantes.codigo_estudiante')
              ->join('personas', 'estudiantes.persona_id', '=', 'personas.persona_id')
              ->orderBy("personas.{$orderBy}")
              ->select('matriculas.*');

        return $query->paginate(20)->withQueryString();
    }

    /**
     * Obtener matrículas de una sección específica
     */
    private function obtenerMatriculasSeccion($seccionId, $search = null)
    {
        $query = Matricula::with(['estudiante.persona', 'seccion.grado'])
            ->where('estado', 'activo')
            ->where('seccion_id', $seccionId);

        if ($search) {
            $query->whereHas('estudiante.persona', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('lastname', 'LIKE', "%{$search}%");
            });
        }

        return $query->join('estudiantes', 'matriculas.codigo_estudiante', '=', 'estudiantes.codigo_estudiante')
            ->join('personas', 'estudiantes.persona_id', '=', 'personas.persona_id')
            ->orderBy('personas.lastname')
            ->select('matriculas.*')
            ->paginate(20)
            ->withQueryString();
    }

    /**
     * Obtener asistencias agrupadas
     */
    private function obtenerAsistencias($matriculas, $periodos)
    {
        return Asistencia::whereIn('codigo_estudiante', $matriculas->pluck('codigo_estudiante'))
            ->whereIn('id_periodo', $periodos->pluck('id_periodo'))
            ->get()
            ->groupBy(['codigo_estudiante', 'id_periodo']);
    }

    /**
     * Calcular estadísticas por periodo
     */
    private function calcularEstadisticas($asistencias, $periodos)
    {
        $hoy = now();
        $asistenciasPorPeriodo = $asistencias->groupBy('id_periodo');
        
        return $periodos->map(function($periodo) use ($asistenciasPorPeriodo, $hoy) {
            $asisPeriodo = $asistenciasPorPeriodo->get($periodo->id_periodo, collect());
            
            $inicio = Carbon::parse($periodo->fecha_inicio);
            $fin = Carbon::parse($periodo->fecha_fin);
            
            // Calcular días de clase
            $diasClases = 0;
            $limiteCalculo = $hoy->lt($fin) ? $hoy : $fin;
            
            for ($d = $inicio->copy(); $d->lte($limiteCalculo); $d->addDay()) {
                if (!$d->isWeekend()) {
                    $diasClases++;
                }
            }
            
            // Contar por estado
            $totales = [];
            foreach (AsistenciaEstado::cases() as $estado) {
                $totales[$estado->value] = $asisPeriodo->where('estado', $estado)->count();
            }
            
            // Calcular porcentajes
            $totalRegistradas = array_sum($totales);
            $porcentajes = [];
            foreach ($totales as $estado => $count) {
                $porcentajes[$estado] = $totalRegistradas > 0 
                    ? round($count / $totalRegistradas * 100, 1) 
                    : 0;
            }
            
            return [
                'periodo' => [
                    'id_periodo' => $periodo->id_periodo,
                    'nombre' => $periodo->nombre,
                    'fecha_inicio' => $periodo->fecha_inicio,
                    'fecha_fin' => $periodo->fecha_fin
                ],
                'diasClases' => $diasClases,
                'totales' => $totales,
                'porcentajes' => $porcentajes,
                'asistenciasCount' => $asisPeriodo->count()
            ];
        })->toArray();
    }

    /**
     * Retornar vista con error
     */
    private function errorView($view, $message)
    {
        return view("pages.admin.{$view}", [
            'error' => $message,
            'matriculas' => collect(),
            'grados' => collect(),
            'secciones' => collect(),
            'periodos' => collect(),
            'asistencias' => collect(),
            'gradoId' => null,      // ✅ Agregado
            'seccionId' => null,    // ✅ Agregado
            'search' => null,       // ✅ Agregado
            'orderBy' => 'nombre'   // ✅ Agregado
        ]);
    }

    /**
     * Obtener secciones por grado (AJAX)
     */
    public function obtenerSeccionesPorGrado(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || !$user->docente) {
                return response()->json(['error' => 'No tiene registro de docente'], 403);
            }

            $gradoId = $request->get('grado_id');
            if (!$gradoId) {
                return response()->json(['secciones' => []]);
            }

            $secciones = $user->docente->secciones()
                ->where('secciones.id_grado', $gradoId)
                ->select('secciones.id_seccion', 'secciones.seccion')
                ->get();

            return response()->json(['secciones' => $secciones]);
            
        } catch (\Exception $e) {
            Log::error('Error AJAX secciones-por-grado: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno'], 500);
        }
    }
}