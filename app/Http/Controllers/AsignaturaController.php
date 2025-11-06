<?php

namespace App\Http\Controllers;

use App\Models\NivelEducativo;
use App\Models\Asignatura;
use App\Models\Grado;
use App\Models\Docente;
use App\Models\Seccion;
use App\Models\SeccionDocente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AsignaturaController extends Controller
{
    /**
     * Mostrar vista para asignar un docente específico a una asignatura
     */
    public function asignar($id)
    {
        $asignatura = Asignatura::where('codigo_asignatura', $id)->firstOrFail();
        $docentes = Docente::with('user.persona')->get();
        return view('pages.admin.asignaturas.asignTeacher5', compact('asignatura', 'docentes'));
    }

    /**
     * Almacenar una nueva asignación de docente (agregar, no reemplazar)
     */
    public function storeAsignacion(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'codigo_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
            'id_seccion' => 'required|exists:secciones,id_seccion',
            'codigo_docente' => 'required|exists:docentes,codigo_docente'
        ]);

        $asignatura = Asignatura::where('codigo_asignatura', $request->codigo_asignatura)->firstOrFail();
        $docente = Docente::where('codigo_docente', $request->codigo_docente)->firstOrFail();
        $seccion = Seccion::findOrFail($request->id_seccion);

        // 1. Crear la relación en asignaturas_docentes
        if (!$asignatura->docentes()->where('asignaturas_docentes.codigo_docente', $docente->codigo_docente)->exists()) {
            $asignatura->docentes()->attach($docente->codigo_docente, [
                'fecha' => now()->toDateString()
            ]);
        }

        // 2. Crear/Activar la relación en secciones_docentes
        try {
            \DB::beginTransaction();

            $asignacionSeccion = SeccionDocente::firstOrCreate(
                [
                    'id_seccion' => $seccion->id_seccion,
                    'codigo_docente' => $docente->codigo_docente
                ],
                [
                    'estado' => true
                ]
            );

            // Si existía y estaba inactiva, actualizar
            if (! $asignacionSeccion->wasRecentlyCreated && ! $asignacionSeccion->estado) {
                $asignacionSeccion->update(['estado' => true]);
            }

            \DB::commit();
            Log::info('SeccionDocente creada/activada', $asignacionSeccion->toArray());

        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Error creando seccion_docente: '. $e->getMessage(), [
                'id_seccion' => $seccion->id_seccion,
                'codigo_docente' => $docente->codigo_docente
            ]);
            // devolver error para que el front lo vea
            return response()->json(['success' => false, 'message' => 'Error al asignar sección: '.$e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => 'Docente asignado con éxito', 'docente' => $docente]);
    }

    public function removeAsignacion(Request $request)
    {
        $request->validate([
            'id_seccion' => 'required|exists:secciones,id_seccion',
            'codigo_docente' => 'required|exists:docentes,codigo_docente'
        ]);

        try {
            // Desactivar la asignación en secciones_docentes
            SeccionDocente::where('id_seccion', $request->id_seccion)
                        ->where('codigo_docente', $request->codigo_docente)
                        ->update(['estado' => false]);

            return response()->json(['success' => true, 'message' => 'Docente desactivado con éxito']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desactivar el docente: ' . $e->getMessage()]);
        }
    }
    /**
     * Agregar un docente adicional a una asignatura (AJAX)
     */
    public function agregarDocente(Request $request)
    {
        $request->validate([
            'codigo_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
            'codigo_docente' => 'required|exists:docentes,codigo_docente'
        ]);

        try {
            DB::beginTransaction();

            $asignatura = Asignatura::where('codigo_asignatura', $request->codigo_asignatura)->firstOrFail();
            $docente = Docente::where('codigo_docente', $request->codigo_docente)->firstOrFail();
            
            // Verificar si la asignación ya existe
            if ($asignatura->docentes()->where('asignaturas_docentes.codigo_docente', $docente->codigo_docente)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'El docente ya está asignado a esta asignatura'
                ], 409);
            }
            
            // Agregar nuevo docente
            $asignatura->docentes()->attach($docente->codigo_docente, [
                'fecha' => now()->toDateString()
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Docente agregado correctamente',
                'docente' => [
                    'codigo_docente' => $docente->codigo_docente,
                    'nombre' => $docente->user->persona->name ?? 'N/A',
                    'apellido' => $docente->user->persona->lastname ?? 'N/A',
                    'fecha_asignacion' => now()->toDateString()
                ]
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al agregar docente: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al agregar el docente: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Actualizar todos los docentes de una asignatura (gestión completa)
     */
    public function updateAsignacion(Request $request)
    {
        $request->validate([
            'codigo_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
            'docentes' => 'array',
            'docentes.*' => 'exists:docentes,codigo_docente'
        ]);

        try {
            DB::beginTransaction();

            $asignatura = Asignatura::where('codigo_asignatura', $request->codigo_asignatura)->firstOrFail();
            $nuevosDocentes = $request->docentes ?? [];
            
            // Obtener docentes actuales
            $docentesActuales = $asignatura->docentes()->pluck('asignaturas_docentes.codigo_docente')->toArray();
            
            // Docentes a agregar (están en nuevos pero no en actuales)
            $docentesAgregar = array_diff($nuevosDocentes, $docentesActuales);
            
            // Docentes a remover (están en actuales pero no en nuevos)
            $docentesRemover = array_diff($docentesActuales, $nuevosDocentes);
            
            // Remover docentes que ya no estarán asignados
            if (!empty($docentesRemover)) {
                $asignatura->docentes()->detach($docentesRemover);
            }
            
            // Agregar nuevos docentes
            if (!empty($docentesAgregar)) {
                $datosAgregar = [];
                foreach ($docentesAgregar as $codigoDocente) {
                    $datosAgregar[$codigoDocente] = ['fecha' => now()->toDateString()];
                }
                $asignatura->docentes()->attach($datosAgregar);
            }
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asignaciones actualizadas correctamente',
                    'agregados' => count($docentesAgregar),
                    'removidos' => count($docentesRemover)
                ]);
            }
            
            return redirect()->route('asignaturas.asignar.docentes')
                ->with('success', 'Asignaciones actualizadas correctamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar asignaciones: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar las asignaciones: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('asignaturas.asignar.docentes')
                ->with('error', 'Error al actualizar las asignaciones: ' . $e->getMessage());
        }
    }

    /**
     * Obtener docentes asignados a una asignatura (AJAX)
     */
    public function getDocentesAsignados($codigoAsignatura)
    {
        try {
            $asignatura = Asignatura::where('codigo_asignatura', $codigoAsignatura)->firstOrFail();
            $docentes = $asignatura->docentes()
                ->with('user.persona')
                ->get()
                ->map(function($docente) {
                    return [
                        'codigo_docente' => $docente->codigo_docente,
                        'nombre' => $docente->user->persona->name ?? 'N/A',
                        'apellido' => $docente->user->persona->lastname ?? 'N/A',
                        'fecha_asignacion' => $docente->pivot->fecha ?? null
                    ];
                });

            return response()->json([
                'success' => true,
                'docentes' => $docentes,
                'total' => $docentes->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener docentes asignados: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los docentes asignados'
            ], 500);
        }
    }

    /**
     * Obtener docentes disponibles para asignar (no asignados a la asignatura)
     */
    public function getDocentesDisponibles($codigoAsignatura)
    {
        try {
            $asignatura = Asignatura::where('codigo_asignatura', $codigoAsignatura)->firstOrFail();
            
            // Obtener IDs de docentes ya asignados
            $docentesAsignados = $asignatura->docentes()->pluck('asignaturas_docentes.codigo_docente');
            
            // Obtener docentes disponibles (no asignados)
            $docentesDisponibles = Docente::with('user.persona')
                ->whereNotIn('codigo_docente', $docentesAsignados)
                ->get()
                ->map(function($docente) {
                    return [
                        'codigo_docente' => $docente->codigo_docente,
                        'nombre' => $docente->user->persona->name ?? 'N/A',
                        'apellido' => $docente->user->persona->lastname ?? 'N/A',
                        'nombre_completo' => ($docente->user->persona->name ?? 'N/A') . ' ' . ($docente->user->persona->lastname ?? 'N/A')
                    ];
                });

            return response()->json([
                'success' => true,
                'docentes' => $docentesDisponibles,
                'total' => $docentesDisponibles->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener docentes disponibles: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los docentes disponibles'
            ], 500);
        }
    }
    /**
     * Obtener todos los docentes activos (en cualquier sección) para una asignatura
     */
    public function getDocentesActivos($codigoAsignatura)
    {
        try {
            // Verificar que la asignatura exista
            $asignatura = Asignatura::where('codigo_asignatura', $codigoAsignatura)->first();
            if (!$asignatura) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asignatura no encontrada'
                ], 404);
            }

            // Obtener docentes que:
            // 1. Estén asignados a la asignatura
            // 2. Tengan al menos una sección activa (gracias a la relación "secciones" que ya filtra por estado=true)
            $docentes = Docente::with('user.persona')
                ->whereHas('asignaturas', function($q) use ($codigoAsignatura) {
                    $q->where('asignaturas.codigo_asignatura', $codigoAsignatura);
                })
                ->whereHas('secciones') // ← Usa la relación existente que ya filtra por estado=true
                ->get()
                ->map(function($docente) use ($codigoAsignatura) {
                    // Obtener la fecha de asignación
                    $fechaAsignacion = null;
                    if ($docente->asignaturas) {
                        $asignaturaRelacion = $docente->asignaturas->firstWhere('codigo_asignatura', $codigoAsignatura);
                        if ($asignaturaRelacion) {
                            $fechaAsignacion = $asignaturaRelacion->pivot->fecha ?? null;
                        }
                    }

                    return [
                        'codigo_docente' => $docente->codigo_docente,
                        'nombre' => $docente->user?->persona?->name ?? 'N/A',
                        'apellido' => $docente->user?->persona?->lastname ?? 'N/A',
                        'fecha_asignacion' => $fechaAsignacion,
                        'secciones_activas' => $docente->secciones()->count() // ← Cuenta secciones activas
                    ];
                });

            return response()->json([
                'success' => true,
                'docentes' => $docentes,
                'total' => $docentes->count()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error REAL en getDocentesActivos: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'codigo_asignatura' => $codigoAsignatura
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno'
            ], 500);
        }
    }

    /**
 * Obtener docentes activos en una sección específica para una asignatura
 */
    public function getDocentesActivosEnSeccion($codigoAsignatura, $idSeccion)
    {
        try {
            // Verificar que la asignatura exista
            $asignatura = Asignatura::where('codigo_asignatura', $codigoAsignatura)->firstOrFail();
            
            // Obtener docentes activos en la sección
            $docentes = Docente::whereHas('secciones', function($query) use ($idSeccion) {
                    $query->where('secciones.id_seccion', $idSeccion)
                        ->where('secciones_docentes.estado', true);
                })
                ->whereHas('asignaturas', function($query) use ($codigoAsignatura) {
                    $query->where('asignaturas.codigo_asignatura', $codigoAsignatura);
                })
                ->with('user.persona')
                ->get()
                ->map(function($docente) {
                    return [
                        'codigo_docente' => $docente->codigo_docente,
                        'nombre' => $docente->user->persona->name ?? 'N/A',
                        'apellido' => $docente->user->persona->lastname ?? 'N/A',
                        'fecha_asignacion' => $docente->asignaturas()
                            ->where('asignaturas.codigo_asignatura', request()->codigo_asignatura)
                            ->first()->pivot->fecha ?? null
                    ];
                });

            return response()->json([
                'success' => true,
                'docentes' => $docentes,
                'total' => $docentes->count()
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error al obtener docentes activos en sección: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los docentes'
            ], 500);
        }
    }
    /**
     * Mostrar vista principal con filtros y lista de asignaturas
     */
    public function show(Request $request)
    {
        $nivelesEducativos = NivelEducativo::all();
        $grados = Grado::select('id_grado', 'nivel_educativo_id', 'grado')->get();
        $docentes = Docente::with('user.persona')->get(); 
        $secciones = Seccion::with('grado')->get(); // <-- ¡Esta línea es nueva!

        // Construir consulta base con relaciones
        $query = Asignatura::with([
            'grado.nivelEducativo',
            'docentes.user.persona'
        ]);
        
        // Aplicar filtros si existen
        if ($request->filled('nivelEducativo')) {
            $query->whereHas('grado', function($q) use ($request) {
                $q->where('nivel_educativo_id', $request->nivelEducativo);
            });
        }
        
        if ($request->filled('grado')) {
            $query->where('id_grado', $request->grado);
        }
        
        $asignaturas = $query->get();
        
        return view('pages.admin.asignaturas.asignTeacher5', compact('nivelesEducativos','grados','asignaturas', 'docentes', 'secciones'));
    }
    
    // Métodos originales mantenidos para compatibilidad
    public function index()
    {
        $asignaturas = Asignatura::with('grado.nivelEducativo')->orderBy('id_grado')->orderBy('nombre')->get();
        return view('pages.admin.asignaturas.index', compact('asignaturas'));
    }

    public function create()
    {
        $grados = Grado::all();
        return view('pages.admin.asignaturas.create', compact('grados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'nombre' => 'required|string|max:100',
        ]);

       $grado = \App\Models\Grado::findOrFail($request->id_grado);

        if (Asignatura::whereHas('grado', function($q) use ($grado) {
                $q->where('nivel_educativo_id', $grado->nivel_educativo_id);
            })
            ->where('id_grado', $grado->id_grado)
            ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->exists()) {
            return back()
                ->withInput()
                ->withErrors(['nombre' => 'Esta asignatura ya existe en este grado y nivel educativo.']);
        }


        Asignatura::create($request->all());
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura registrada correctamente.');
    }

    public function edit($id)
    {
        $asignatura = Asignatura::findOrFail($id);
        $grados = Grado::all();
        return view('pages.admin.asignaturas.edit', compact('asignatura', 'grados'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'nombre' => 'required|string|max:100',
        ]);

        $asignatura = Asignatura::findOrFail($id);

        $grado = \App\Models\Grado::findOrFail($request->id_grado);

        if (Asignatura::whereHas('grado', function($q) use ($grado) {
                $q->where('nivel_educativo_id', $grado->nivel_educativo_id);
            })
            ->where('id_grado', $grado->id_grado)
            ->whereRaw('LOWER(nombre) = ?', [strtolower($request->nombre)])
            ->where('codigo_asignatura', '!=', $id)
            ->exists()) {
            return back()
                ->withInput()
                ->withErrors(['nombre' => 'Esta asignatura ya existe en este grado y nivel educativo.']);
        }

        $asignatura->update($request->all());
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura actualizada correctamente.');
    }

    public function destroy($id)
    {
        $asignatura = Asignatura::findOrFail($id);

        if ($asignatura->competencias()->exists()) {
            return redirect()->route('asignaturas.index')->with('error', 'No se puede eliminar la asignatura porque tiene relaciones activas.');
        }

        $asignatura->delete();
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura eliminada correctamente.');
    }

}

