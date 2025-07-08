<?php

namespace App\Http\Controllers;

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
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Obtener el registro de docente
        $docente = $user->docente;
        
        // Si no existe el registro de docente, retornar vista con mensaje
        if (!$docente) {
            return view('pages.admin.asistencia.index', [
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
        
        // Obtener secciones del docente con sus grados
        $seccionesDocente = $docente->secciones()
            ->with(['grado.nivelEducativo'])
            ->get();
        
        // Si no tiene secciones asignadas
        if ($seccionesDocente->isEmpty()) {
            return view('pages.admin.asistencia.index', [
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
        
        // Obtener grados únicos de las secciones del docente
        $grados = Grado::whereIn('id_grado', $seccionesDocente->pluck('id_grado')->unique())->get();
        
        // Filtros
        $gradoId = $request->get('grado_id');
        $seccionId = $request->get('seccion_id');
        $search = $request->get('search');
        $orderBy = $request->get('order_by', 'nombre');
        
        // Obtener secciones si se seleccionó un grado
        $secciones = collect();
        if ($gradoId) {
            $secciones = $seccionesDocente->where('id_grado', $gradoId);
        }
        
        // Query base para estudiantes - solo de las secciones del docente
        $seccionesIds = $seccionesDocente->pluck('id_seccion')->toArray();
        $estudiantesQuery = Matricula::where('estado', 'activo')
            ->whereIn('seccion_id', $seccionesIds)
            ->whereHas('estudiante.persona', function ($q) use ($search) {
                if ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('lastname', 'LIKE', "%{$search}%");
                }
            })
            ->with(['estudiante.persona', 'seccion.grado']);

        
        $matriculasSample = Matricula::whereIn('seccion_id', $seccionesIds)
            ->take(5)
            ->get(['codigo_matricula', 'seccion_id', 'estado']);
        
        \Log::info('Muestra de matrículas: ' . $matriculasSample->toJson());
        
        // Aplicar filtros adicionales
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
        
        // Ordenamiento
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
        
        // Paginación
        $matriculasSinPaginar = $estudiantesQuery->get()->sortBy(function ($matricula) use ($orderBy) {
    $persona = $matricula->estudiante->persona;
    return $orderBy === 'apellido'
        ? $persona->lastname . ' ' . $persona->name
        : $persona->name . ' ' . $persona->lastname;
})->values();

// Paginación manual
$page = request()->get('page', 1);
$perPage = 20;
$offset = ($page - 1) * $perPage;

$matriculas = new LengthAwarePaginator(
    $matriculasSinPaginar->slice($offset, $perPage)->values(),
    $matriculasSinPaginar->count(),
    $perPage,
    $page,
    ['path' => request()->url(), 'query' => request()->query()]
);

        \Log::info('Total de matrículas encontradas después de filtros: ' . $matriculas->total());

        // Obtener periodos activos
        $hoy = Carbon::now();
        $periodos = Periodo::where('fecha_inicio', '<=', $hoy)
            ->orderBy('fecha_inicio')
            ->get();
        
        // Obtener asistencias de los estudiantes para los periodos activos
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
        
        // Inicializar variables
        $matriculas = collect();
        $asistenciasExistentes = [];
        $mensajePeriodo = null;
        $periodos = Periodo::orderBy('fecha_inicio', 'desc')->get();
        
        // Obtener secciones del docente
        $seccionesDocente = $docente->secciones()
            ->with(['grado.nivelEducativo'])
            ->get();
        
        // Si no tiene secciones asignadas
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
        
        // Obtener grados únicos del docente
        $grados = Grado::whereIn('id_grado', $seccionesDocente->pluck('id_grado')->unique())->get();
        
        // Filtros
        $gradoId = $request->get('grado_id');
        $seccionId = $request->get('seccion_id');
        $search = $request->get('search');
        $fecha = $request->get('fecha', Carbon::now()->format('Y-m-d'));
        
        // Obtener periodo activo según la fecha
        $periodo = Periodo::where('fecha_inicio', '<=', $fecha)
            ->where('fecha_fin', '>=', $fecha)
            ->first();
            
        // Si no hay periodo activo
        if (!$periodo) {
            $mensajePeriodo = 'No hay un periodo activo para la fecha seleccionada.';
            if ($periodos->isEmpty()) {
                $mensajePeriodo .= ' No hay periodos registrados en el sistema.';
            } else {
                $mensajePeriodo .= ' Seleccione una fecha dentro de un periodo válido.';
            }
        }
        
        // Obtener secciones si se seleccionó un grado
        $secciones = collect();
        if ($gradoId) {
            $secciones = $seccionesDocente->where('id_grado', $gradoId);
        }
        
        // Solo buscar estudiantes si hay grado, sección y periodo
        if ($gradoId && $seccionId && $periodo) {
            // Verificar que el docente tenga acceso a esta sección
            if (!$seccionesDocente->pluck('id_seccion')->contains($seccionId)) {
                return redirect()->route('asistencias.create')
                    ->with('error', 'No tiene acceso a la sección seleccionada.');
            }
            
            // Query para estudiantes de la sección
            $estudiantesQuery = Matricula::query()
                ->with(['estudiante.persona', 'seccion.grado'])
                ->where('estado', 'activo')
                ->where('seccion_id', $seccionId);
            
            // Aplicar búsqueda
            if ($search) {
                $estudiantesQuery->whereHas('estudiante.persona', function($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('lastname', 'LIKE', "%{$search}%");
                });
            }
            
            // Ordenar por apellido y nombre
            $estudiantesQuery->join('estudiantes', 'matriculas.codigo_estudiante', '=', 'estudiantes.codigo_estudiante')
                ->join('personas', 'estudiantes.persona_id', '=', 'personas.persona_id')
                ->orderBy('personas.lastname')
                ->orderBy('personas.name')
                ->select('matriculas.*');
            
            // Paginación
            $matriculas = $estudiantesQuery->paginate(20)->withQueryString();
            
            // Verificar si ya existen asistencias para esta fecha
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
        $request->validate([
            'fecha' => 'required|date',
            'id_periodo' => 'required|exists:periodos,id_periodo',
            'asistencias' => 'required|array',
            'asistencias.*.codigo_estudiante' => 'required|exists:estudiantes,codigo_estudiante',
            'asistencias.*.estado' => 'required|in:Presente,Ausente,Justificado,Tarde',
        ]);

        $fecha = $request->fecha;
        $idPeriodo = $request->id_periodo;
        $asistencias = $request->asistencias;

        // Verificar que no existan asistencias duplicadas
        foreach ($asistencias as $asistencia) {
            $existe = Asistencia::where('codigo_estudiante', $asistencia['codigo_estudiante'])
                ->where('fecha', $fecha)
                ->where('id_periodo', $idPeriodo)
                ->exists();

            if (!$existe) {
                Asistencia::create([
                    'codigo_estudiante' => $asistencia['codigo_estudiante'],
                    'fecha' => $fecha,
                    'id_periodo' => $idPeriodo,
                    'estado' => $asistencia['estado'],
                    'observacion' => $asistencia['observacion'] ?? null,
                    'justificacion' => $asistencia['justificacion'] ?? null,
                ]);
            }
        }

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencias registradas correctamente.');
    }

    public function edit($codigo_estudiante)
    {
        // Este método se puede implementar para editar asistencias individuales
        // Por ahora redirigimos al index
        return redirect()->route('asistencias.index');
    }
}