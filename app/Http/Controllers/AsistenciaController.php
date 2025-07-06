<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use App\Models\Asistencia;
use App\Models\Asignatura;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\Periodo;
use App\Models\Matricula;
use App\Models\Estudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $docente = $user->docente;

        if (!$docente) {
            return view('asistencias.index', [
                'error' => 'Su usuario no tiene un registro de docente asociado. Por favor, contacte al administrador.',
                'matriculas' => collect(),
                'grados' => collect(),
                'secciones' => collect(),
                'periodos' => collect(),
                'asistencias' => collect(),
                'gradoId' => null,
                'seccionId' => null,
                'search' => null,
                'orderBy' => 'nombre'
            ]);
        }

        $seccionesDocente = $docente->secciones()
            ->with(['grado.nivelEducativo'])
            ->get();

        if ($seccionesDocente->isEmpty()) {
            return view('asistencias.index', [
                'error' => 'No tiene secciones asignadas. Por favor, contacte al administrador.',
                'matriculas' => collect(),
                'grados' => collect(),
                'secciones' => collect(),
                'periodos' => collect(),
                'asistencias' => collect(),
                'gradoId' => null,
                'seccionId' => null,
                'search' => null,
                'orderBy' => 'nombre'
            ]);
        }

        $grados = Grado::whereIn('id_grado', $seccionesDocente->pluck('id_grado')->unique())->get();

        $gradoId = $request->get('grado_id');
        $seccionId = $request->get('seccion_id');
        $search = $request->get('search');
        $orderBy = $request->get('order_by', 'nombre');

        $secciones = collect();
        if ($gradoId) {
            $secciones = $seccionesDocente->where('id_grado', $gradoId);
        }

        $seccionesIds = $seccionesDocente->pluck('id_seccion')->toArray();
        $estudiantesQuery = Matricula::query()
            ->with(['estudiante.persona', 'seccion.grado'])
            ->where('estado', true)
            ->whereIn('seccion_id', $seccionesIds);

        if ($gradoId) {
            $estudiantesQuery->whereHas('seccion', function($q) use ($gradoId) {
                $q->where('id_grado', $gradoId);
            });
        }
        
        if ($seccionId) {
            $estudiantesQuery->where('seccion_id', $seccionId);
        }
        
        if ($search) {
            $estudiantesQuery->whereHas('estudiante.persona', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('lastname', 'LIKE', "%{$search}%");
            });
        }

        if ($orderBy === 'apellido') {
            $estudiantesQuery->join('estudiantes', 'matriculas.codigo_estudiante', '=', 'estudiantes.codigo_estudiante')
                ->join('personas', 'estudiantes.persona_id', '=', 'personas.persona_id')
                ->orderBy('personas.lastname')
                ->orderBy('personas.name')
                ->select('matriculas.*');
        } else {
            $estudiantesQuery->join('estudiantes', 'matriculas.codigo_estudiante', '=', 'estudiantes.codigo_estudiante')
                ->join('personas', 'estudiantes.persona_id', '=', 'personas.persona_id')
                ->orderBy('personas.name')
                ->select('matriculas.*');
        }

        $matriculas = $estudiantesQuery->paginate(20)->withQueryString();

        $hoy = Carbon::now();
        $periodos = Periodo::where('fecha_inicio', '<=', $hoy)
            ->orderBy('fecha_inicio')
            ->get();

        $estudiantesIds = $matriculas->pluck('codigo_estudiante')->toArray();
        $periodosIds = $periodos->pluck('id_periodo')->toArray();
        
        $asistencias = Asistencia::whereIn('codigo_estudiante', $estudiantesIds)
            ->whereIn('id_periodo', $periodosIds)
            ->get()
            ->groupBy(['codigo_estudiante', 'id_periodo']);
        
        return view('pages.admin.asistencia.index', compact(
            'matriculas',
            'grados',
            'secciones',
            'periodos',
            'asistencias',
            'gradoId',
            'seccionId',
            'search',
            'orderBy'
        ));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $docente = $user->docente;
        
        if (!$docente) {
            return redirect()->route('asistencias.index')
                ->with('error', 'No tiene un registro de docente asociado.');
        }

        $matriculas = collect();
        $asistenciasExistentes = [];
        $mensajePeriodo = null;
        $periodos = Periodo::orderBy('fecha_inicio', 'desc')->get();

        $seccionesDocente = $docente->secciones()
            ->with(['grado.nivelEducativo'])
            ->get();

        if ($seccionesDocente->isEmpty()) {
            return view('pages.admin.asistencia.create', [
                'matriculas' => $matriculas,
                'grados' => collect(),
                'secciones' => collect(),
                'periodo' => null,
                'gradoId' => null,
                'seccionId' => null,
                'search' => null,
                'fecha' => Carbon::now()->format('Y-m-d'),
                'asistenciasExistentes' => [],
                'mensajePeriodo' => 'No tiene secciones asignadas. Contacte al administrador.',
                'periodos' => $periodos
            ]);
        }

        $grados = Grado::whereIn('id_grado', $seccionesDocente->pluck('id_grado')->unique())->get();

        $gradoId = $request->get('grado_id');
        $seccionId = $request->get('seccion_id');
        $search = $request->get('search');
        $fecha = $request->get('fecha', Carbon::now()->format('Y-m-d'));

        $periodo = Periodo::where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha)
            ->first();

        if (!$periodo) {
            $mensajePeriodo = 'No hay un periodo activo para la fecha seleccionada.';
            if ($periodos->isEmpty()) {
                $mensajePeriodo .= ' No hay periodos registrados en el sistema.';
            } else {
                $mensajePeriodo .= ' Seleccione una fecha dentro de un periodo válido.';
            }
        }

        $secciones = collect();
        if ($gradoId) {
            $secciones = $seccionesDocente->where('id_grado', $gradoId);
        }

        if ($gradoId && $seccionId && $periodo) {

            if (!$seccionesDocente->pluck('id_seccion')->contains($seccionId)) {
                return redirect()->route('asistencias.create')
                    ->with('error', 'No tiene acceso a la sección seleccionada.');
            }

            $estudiantesQuery = Matricula::query()
                ->with(['estudiante.persona', 'seccion.grado'])
                ->where('estado', true)
                ->where('seccion_id', $seccionId);

            if ($search) {
                $estudiantesQuery->whereHas('estudiante.persona', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('lastname', 'LIKE', "%{$search}%");
                });
            }

            $estudiantesQuery->join('estudiantes', 'matriculas.codigo_estudiante', '=', 'estudiantes.codigo_estudiante')
                ->join('personas', 'estudiantes.persona_id', '=', 'personas.persona_id')
                ->orderBy('personas.lastname')
                ->orderBy('personas.name')
                ->select('matriculas.*');

            $matriculas = $estudiantesQuery->paginate(20)->withQueryString();

            $estudiantesIds = $matriculas->pluck('codigo_estudiante')->toArray();
            $asistenciasExistentes = Asistencia::whereIn('codigo_estudiante', $estudiantesIds)
                ->where('fecha', $fecha)
                ->where('id_periodo', $periodo->id_periodo)
                ->pluck('codigo_estudiante')
                ->toArray();
        }
        
        return view('pages.admin.asistencia.create', compact(
            'matriculas',
            'grados',
            'secciones',
            'periodo',
            'gradoId',
            'seccionId',
            'search',
            'fecha',
            'asistenciasExistentes',
            'mensajePeriodo',
            'periodos'
        ));
    }

    public function store(Request $request)
    {
       $fecha = $request->input('fecha');
        $periodo = Periodo::findOrFail($request->input('id_periodo'));

        if (Carbon::parse($fecha)->isWeekend()) {
            return back()
                ->withInput()
                ->with('error', 'No puedes registrar asistencia en sábado ni domingo.');
        }

        if (Carbon::parse($fecha)->lt($periodo->fecha_inicio) 
            || Carbon::parse($fecha)->gt($periodo->fecha_fin)) {
            return back()
                ->withInput()
                ->with('error', 'La fecha está fuera del rango del periodo seleccionado.');
        }

        foreach ($request->asistencias as $asis) {
            $existe = Asistencia::where('codigo_estudiante', $asis['codigo_estudiante'])
                ->where('fecha', $fecha)
                ->where('id_periodo', $periodo->id_periodo)
                ->exists();

            if (! $existe) {
                Asistencia::create([
                    'codigo_estudiante' => $asis['codigo_estudiante'],
                    'fecha'             => $fecha,
                    'id_periodo'        => $periodo->id_periodo,
                    'estado'            => $asis['estado'],
                    'observacion'       => $asis['observacion'] ?? null,
                    'justificacion'     => $asis['justificacion'] ?? null,
                ]);
            }
        }

        return redirect()->route('asistencias.index')
                        ->with('success', 'Asistencias registradas correctamente.');
    }

    public function edit($codigo_estudiante)
    {
        $user = Auth::user();
        $docente = $user->docente;
        
        if (!$docente) {
            return redirect()->route('asistencias.index')
                ->with('error', 'No tiene un registro de docente asociado.');
        }

        $matricula = Matricula::with('estudiante.persona', 'seccion.grado')
            ->where('codigo_estudiante', $codigo_estudiante)
            ->where('estado', true)
            ->first();

        if (!$matricula) {
            return redirect()->route('asistencias.index')
                ->with('error', 'Estudiante no encontrado o no matriculado.');
        }

        if (!$docente->secciones->pluck('id_seccion')->contains($matricula->seccion_id)) {
            return redirect()->route('asistencias.index')
                ->with('error', 'No tiene permiso para editar las asistencias de este estudiante.');
        }

        $periodos = Periodo::orderBy('fecha_inicio')->get();

        $asistencias = Asistencia::where('codigo_estudiante', $codigo_estudiante)
            ->orderBy('fecha', 'desc')
            ->get()
            ->groupBy('id_periodo');

        $asistenciasPorPeriodo = [];
        foreach ($periodos as $periodo) {
            $asistenciasPeriodo = $asistencias->get($periodo->id_periodo, collect());

            $estadisticas = [
                'Presente' => $asistenciasPeriodo->where('estado', 'Presente')->count(),
                'Ausente' => $asistenciasPeriodo->where('estado', 'Ausente')->count(),
                'Justificado' => $asistenciasPeriodo->where('estado', 'Justificado')->count(),
                'Tarde' => $asistenciasPeriodo->where('estado', 'Tarde')->count(),
                'total' => $asistenciasPeriodo->count()
            ];

            $asistenciasPorPeriodo[] = [
                'periodo' => $periodo,
                'asistencias' => $asistenciasPeriodo,
                'estadisticas' => $estadisticas
            ];
        }

        return view('pages.admin.asistencia.edit', compact(
            'matricula',
            'asistenciasPorPeriodo'
        ));
    }

    public function update(Request $request, $codigo_estudiante)
    {
        $user = Auth::user();
        $docente = $user->docente;
        
        if (!$docente) {
            return redirect()->route('asistencias.index')
                ->with('error', 'No tiene un registro de docente asociado.');
        }

        $matricula = Matricula::where('codigo_estudiante', $codigo_estudiante)
            ->where('estado', true)
            ->first();

        if (!$matricula || !$docente->secciones->pluck('id_seccion')->contains($matricula->seccion_id)) {
            return redirect()->route('asistencias.index')
                ->with('error', 'No tiene permiso para editar las asistencias de este estudiante.');
        }

        $request->validate([
            'asistencias' => 'required|array',
            'asistencias.*.id_asistencia' => 'required|exists:asistencias,id_asistencia',
            'asistencias.*.estado' => 'required|in:Presente,Ausente,Justificado,Tarde',
            'asistencias.*.observacion' => 'nullable|string|max:255',
            'asistencias.*.justificacion' => 'nullable|string|max:255'
        ]);

        foreach ($request->asistencias as $asistenciaData) {
            $asistencia = Asistencia::find($asistenciaData['id_asistencia']);

            if ($asistencia && $asistencia->codigo_estudiante == $codigo_estudiante) {
                $asistencia->update([
                    'estado' => $asistenciaData['estado'],
                    'observacion' => $asistenciaData['observacion'] ?? null,
                    'justificacion' => $asistenciaData['justificacion'] ?? null
                ]);
            }
        }

        return redirect()->route('asistencias.index', $codigo_estudiante)
            ->with('success', 'Asistencias actualizadas correctamente.');
    }

    public function show($codigo_estudiante)
    {
        $user = Auth::user();
        $docente = $user->docente;
        if (!$docente) {
            return redirect()->route('asistencias.index')
                ->with('error', 'No tiene un registro de docente asociado.');
        }
        $matricula = Matricula::with('estudiante.persona', 'seccion.grado')
            ->where('codigo_estudiante', $codigo_estudiante)
            ->firstOrFail();

        if (!$docente->secciones->pluck('id_seccion')->contains($matricula->seccion_id)) {
            return redirect()->route('asistencias.index')
                ->with('error', 'No tienes permiso para ver este estudiante.');
        }

        $hoy = Carbon::today();
        
        $periodos = Periodo::orderBy('fecha_inicio')->get();

        if ($periodos->isEmpty()) {
            return redirect()->route('asistencias.index')
                ->with('error', 'No hay periodos registrados en el sistema.');
        }

        $asistencias = Asistencia::where('codigo_estudiante', $codigo_estudiante)
            ->get()
            ->groupBy('id_periodo');

        $statsPorPeriodo = [];
        foreach ($periodos as $periodo) {
            $asisPeriodo = $asistencias->get($periodo->id_periodo, collect());

            $inicio = Carbon::parse($periodo->fecha_inicio);
            $fin = Carbon::parse($periodo->fecha_fin);

            $diasClases = 0;
            
            if ($inicio->greaterThanOrEqualTo($hoy)) {

                $estadoPeriodo = 'futuro';
                $diasClases = 0;
            } elseif ($fin->lt($hoy)) {

                $estadoPeriodo = 'pasado';

                for ($d = $inicio->copy(); $d->lte($fin); $d->addDay()) {
                    if (!$d->isWeekend()) {
                        $diasClases++;
                    }
                }
            } else {

                $estadoPeriodo = 'actual';

                for ($d = $inicio->copy(); $d->lte($hoy); $d->addDay()) {
                    if (!$d->isWeekend()) {
                        $diasClases++;
                    }
                }
            }

            $totales = [
                'Presente'    => 0,
                'Ausente'     => 0,
                'Justificado' => 0,
                'Tarde'       => 0,
            ];

            foreach ($asisPeriodo as $a) {
                if (isset($totales[$a->estado->value])) {
                    $totales[$a->estado->value]++;
                }
            }

            $porcentajes = [];
            foreach ($totales as $estado => $count) {
                $porcentajes[$estado] = $diasClases > 0
                    ? round($count / $diasClases * 100)
                    : 0;
            }

            $diasTotalesPeriodo = 0;
            for ($d = $inicio->copy(); $d->lte($fin); $d->addDay()) {
                if (!$d->isWeekend()) {
                    $diasTotalesPeriodo++;
                }
            }

            $statsPorPeriodo[] = [
                'periodo'          => $periodo,
                'estadoPeriodo'    => $estadoPeriodo,
                'diasClases'       => $diasClases,
                'diasTotalesPeriodo' => $diasTotalesPeriodo,
                'totales'          => $totales,
                'porcentajes'      => $porcentajes,
                'asistenciasCount' => $asisPeriodo->count()
            ];
        }

        return view('pages.admin.asistencia.show', compact(
            'matricula', 'statsPorPeriodo'
        ));
    }


    public function obtenerSeccionesPorGrado(Request $request)
    {
        $response = null;
        try {
            $user = Auth::user();
            if (!$user || !$user->docente) {
                $response = response()->json(['error' => 'No tiene registro de docente'], 403);
            } else {
                $gradoId = $request->get('grado_id');
                if (!$gradoId) {
                    $response = response()->json(['secciones' => []]);
                } else {
                    $secciones = $user->docente
                        ->secciones()
                        ->where('secciones.id_grado', $gradoId)
                        ->select('secciones.id_seccion', 'secciones.seccion')
                        ->get();
                    $response = response()->json(['secciones' => $secciones]);
                }
            }
        } catch (\Throwable $e) {
            \Log::error('Error AJAX secciones-por-grado: '.$e->getMessage());
            $response = response()->json(['error' => 'Error interno'], 500);
        }
        return $response;
    }
}