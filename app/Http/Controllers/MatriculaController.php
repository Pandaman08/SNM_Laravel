<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\TipoMatricula;
use App\Models\AnioEscolar;
use App\Models\Tutor;
use App\Models\Estudiante;
use App\Models\NivelEducativo;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\Asignatura;
use App\Models\DetalleAsignatura;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MatriculaController extends Controller
{
    /**
     * Lista de todas las matrículas (admin/secretaria)
     */
    public function index()
    {
        $matriculas = Matricula::with([
            'estudiante', 
            'tipoMatricula', 
            'anioEscolar',
            'seccion.grado.nivelEducativo'
        ])->orderBy('fecha', 'desc')->get();
        
        return view('pages.admin.matriculas.index', compact('matriculas'));
    }

    /**
     * Vista para crear matrícula (admin/secretaria)
     */
    public function create()
    {
        // Redirigir a tutores a su vista específica
        if (Auth::check() && Auth::user()->tutor) {
            return redirect()->route('matriculas.create-tutor');
        }

        $tiposMatricula = TipoMatricula::all();
        $aniosEscolares = AnioEscolar::all();
        $tutores = Tutor::with('user')->get();
        $nivelesEducativos = NivelEducativo::activos()->get();
        $secciones = Seccion::with('grado.nivelEducativo')->get();
        
        return view('pages.admin.matriculas.create', compact(
            'tiposMatricula', 
            'aniosEscolares', 
            'tutores', 
            'nivelesEducativos', 
            'secciones'
        ));
    }

    /**
     * Vista específica para tutores
     */
    public function createTutor()
    {
        $tiposMatricula = TipoMatricula::all();
        $aniosEscolares = AnioEscolar::all();
        $nivelesEducativos = NivelEducativo::activos()->get();
        $grados = Grado::with('nivelEducativo')
            ->orderBy('grado')
            ->get(['id_grado', 'grado', 'nivel_educativo_id']);
        $secciones = Seccion::with('grado.nivelEducativo')
            ->orderBy('seccion')
            ->get(['id_seccion', 'seccion', 'id_grado']);

        return view('pages.admin.matriculas.create-tutor', compact(
            'tiposMatricula', 
            'aniosEscolares', 
            'nivelesEducativos', 
            'grados', 
            'secciones'
        ));
    }

    /**
     * Guardar matrícula desde admin/secretaria
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tipo_matricula' => 'required|exists:tipos_matricula,id_tipo_matricula',
            'id_anio_escolar' => 'required|exists:anios_escolares,id_anio_escolar',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:estudiantes,dni',
            'sexo' => 'required|in:masculino,femenino',
            'fecha_nacimiento' => 'required|date',
            'pais' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'distrito' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'lengua_materna' => 'required|string|max:50',
            'religion' => 'nullable|string|max:50',
            'seccion_id' => 'required|exists:secciones,id_seccion',
            'fecha' => 'required|date',
            'id_tutor' => 'required|exists:tutores,id_tutor',
            'tipo_relacion' => 'required|string|max:50'
        ]);

        DB::beginTransaction();
        
        try {
            // Crear estudiante
            $estudiante = Estudiante::create([
                'codigo_estudiante' => $this->generarCodigoEstudiante(),
                'nombre' => $validated['nombre'],
                'apellidos' => $validated['apellidos'],
                'dni' => $validated['dni'],
                'sexo' => $validated['sexo'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'pais' => $validated['pais'],
                'provincia' => $validated['provincia'],
                'distrito' => $validated['distrito'],
                'departamento' => $validated['departamento'],
                'lengua_materna' => $validated['lengua_materna'],
                'religion' => $validated['religion'],
                'activo' => true
            ]);

            // Crear matrícula
            $matricula = Matricula::create([
                'codigo_matricula' => $this->generarCodigoMatricula(),
                'codigo_estudiante' => $estudiante->codigo_estudiante,
                'id_tipo_matricula' => $validated['id_tipo_matricula'],
                'id_anio_escolar' => $validated['id_anio_escolar'],
                'seccion_id' => $validated['seccion_id'],
                'fecha' => $validated['fecha']
            ]);

            // Crear relación estudiante-tutor
            DB::table('estudiantes_tutores')->insert([
                'codigo_estudiante' => $estudiante->codigo_estudiante,
                'id_tutor' => $validated['id_tutor'],
                'tipo_relacion' => $validated['tipo_relacion'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Crear detalles de asignaturas automáticamente
            $this->crearDetallesAsignaturas($matricula);

            DB::commit();

            return redirect()->route('matriculas.index')
                           ->with('success', 'Matrícula registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al procesar la matrícula: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Guardar solicitud de matrícula desde tutor
     */
    public function storeTutor(Request $request)
    {
        $validated = $request->validate([
            'id_tipo_matricula' => 'required|exists:tipos_matricula,id_tipo_matricula',
            'id_anio_escolar' => 'required|exists:anios_escolares,id_anio_escolar',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:estudiantes,dni',
            'sexo' => 'required|in:masculino,femenino',
            'fecha_nacimiento' => 'required|date',
            'pais' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'distrito' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'lengua_materna' => 'required|string|max:50',
            'religion' => 'nullable|string|max:50',
            'seccion_id' => 'required|exists:secciones,id_seccion',
            'fecha' => 'required|date',
            'tipo_relacion' => 'required|string|max:50'
        ]);

        DB::beginTransaction();
        
        try {
            // Crear estudiante con estado pendiente (activo = false)
            $estudiante = Estudiante::create([
                'codigo_estudiante' => null, // Se generará cuando se apruebe
                'nombre' => $validated['nombre'],
                'apellidos' => $validated['apellidos'],
                'dni' => $validated['dni'],
                'sexo' => $validated['sexo'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'pais' => $validated['pais'],
                'provincia' => $validated['provincia'],
                'distrito' => $validated['distrito'],
                'departamento' => $validated['departamento'],
                'lengua_materna' => $validated['lengua_materna'],
                'religion' => $validated['religion'],
                'activo' => false // Pendiente de aprobación
            ]);

            // Crear matrícula en estado pendiente
            $matricula = Matricula::create([
                'codigo_matricula' => null, // Se generará cuando se apruebe
                'codigo_estudiante' => $estudiante->codigo_estudiante,
                'id_tipo_matricula' => $validated['id_tipo_matricula'],
                'id_anio_escolar' => $validated['id_anio_escolar'],
                'seccion_id' => $validated['seccion_id'],
                'fecha' => $validated['fecha']
            ]);

            // Crear relación estudiante-tutor
            DB::table('estudiantes_tutores')->insert([
                'codigo_estudiante' => $estudiante->codigo_estudiante,
                'id_tutor' => Auth::user()->tutor->id_tutor,
                'tipo_relacion' => $validated['tipo_relacion'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return redirect()->route('matriculas.mis-matriculas')
                           ->with('success', 'Solicitud de matrícula enviada exitosamente. Será revisada por la administración.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al enviar la solicitud: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Ver matrículas del tutor logueado
     */
    public function misMatriculas()
    {
        $matriculas = Matricula::whereHas('estudiante', function($query) {
            $query->whereHas('tutores', function($q) {
                $q->where('tutores.id_tutor', Auth::user()->tutor->id_tutor);
            });
        })->with([
            'estudiante', 
            'tipoMatricula', 
            'anioEscolar',
            'seccion.grado.nivelEducativo'
        ])->orderBy('fecha', 'desc')->get();

        return view('matriculas.mis-matriculas', compact('matriculas'));
    }

    /**
     * Mostrar una matrícula específica
     */
    public function show($codigo_matricula)
    {
        $matricula = Matricula::with([
            'estudiante',
            'tipoMatricula',
            'anioEscolar',
            'seccion.grado.nivelEducativo',
            'detallesAsignatura.asignatura'
        ])->where('codigo_matricula', $codigo_matricula)->firstOrFail();

        return view('matriculas.show', compact('matricula'));
    }

    /**
     * Editar matrícula (admin/secretaria)
     */
    public function edit($codigo_matricula)
    {
        $matricula = Matricula::with([
            'estudiante',
            'tipoMatricula',
            'anioEscolar',
            'seccion.grado.nivelEducativo'
        ])->where('codigo_matricula', $codigo_matricula)->firstOrFail();

        $tiposMatricula = TipoMatricula::all();
        $aniosEscolares = AnioEscolar::all();
        $tutores = Tutor::with('user')->get();
        $nivelesEducativos = NivelEducativo::activos()->get();
        $secciones = Seccion::with('grado.nivelEducativo')->get();

        return view('matriculas.edit', compact(
            'matricula',
            'tiposMatricula', 
            'aniosEscolares', 
            'tutores', 
            'nivelesEducativos', 
            'secciones'
        ));
    }

    /**
     * Actualizar matrícula
     */
    public function update(Request $request, $codigo_matricula)
    {
        $matricula = Matricula::where('codigo_matricula', $codigo_matricula)->firstOrFail();

        $validated = $request->validate([
            'id_tipo_matricula' => 'required|exists:tipos_matricula,id_tipo_matricula',
            'id_anio_escolar' => 'required|exists:anios_escolares,id_anio_escolar',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:estudiantes,dni,' . $matricula->codigo_estudiante . ',codigo_estudiante',
            'sexo' => 'required|in:masculino,femenino',
            'fecha_nacimiento' => 'required|date',
            'pais' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'distrito' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'lengua_materna' => 'required|string|max:50',
            'religion' => 'nullable|string|max:50',
            'seccion_id' => 'required|exists:secciones,id_seccion',
            'fecha' => 'required|date'
        ]);

        DB::beginTransaction();
        
        try {
            // Actualizar estudiante
            $matricula->estudiante->update([
                'nombre' => $validated['nombre'],
                'apellidos' => $validated['apellidos'],
                'dni' => $validated['dni'],
                'sexo' => $validated['sexo'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'pais' => $validated['pais'],
                'provincia' => $validated['provincia'],
                'distrito' => $validated['distrito'],
                'departamento' => $validated['departamento'],
                'lengua_materna' => $validated['lengua_materna'],
                'religion' => $validated['religion']
            ]);

            // Actualizar matrícula
            $matricula->update([
                'id_tipo_matricula' => $validated['id_tipo_matricula'],
                'id_anio_escolar' => $validated['id_anio_escolar'],
                'seccion_id' => $validated['seccion_id'],
                'fecha' => $validated['fecha']
            ]);

            DB::commit();

            return redirect()->route('matriculas.index')
                           ->with('success', 'Matrícula actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al actualizar la matrícula: ' . $e->getMessage())
                        ->withInput();
        }
    }

    /**
     * Eliminar matrícula
     */
    public function destroy($codigo_matricula)
    {
        DB::beginTransaction();
        
        try {
            $matricula = Matricula::where('codigo_matricula', $codigo_matricula)->firstOrFail();
            
            // Eliminar detalles de asignaturas
            DetalleAsignatura::where('codigo_matricula', $codigo_matricula)->delete();
            
            // Eliminar relación estudiante-tutor
            DB::table('estudiantes_tutores')
              ->where('codigo_estudiante', $matricula->codigo_estudiante)
              ->delete();
            
            // Eliminar estudiante y matrícula
            $matricula->estudiante->delete();
            $matricula->delete();

            DB::commit();

            return redirect()->route('matriculas.index')
                           ->with('success', 'Matrícula eliminada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al eliminar la matrícula: ' . $e->getMessage());
        }
    }

    /**
     * Aprobar matrícula (solo admin/secretaria)
     */
    public function aprobar($codigo_matricula)
    {
        $matricula = Matricula::where('codigo_matricula', $codigo_matricula)->firstOrFail();
        
        if ($matricula->estudiante->activo) {
            return back()->with('error', 'Esta matrícula ya ha sido aprobada.');
        }

        DB::beginTransaction();
        
        try {
            // Generar códigos si no existen
            if (!$matricula->estudiante->codigo_estudiante) {
                $matricula->estudiante->update([
                    'codigo_estudiante' => $this->generarCodigoEstudiante()
                ]);
            }
            
            if (!$matricula->codigo_matricula) {
                $matricula->update([
                    'codigo_matricula' => $this->generarCodigoMatricula()
                ]);
            }
            
            // Activar estudiante
            $matricula->estudiante->update(['activo' => true]);

            // Crear detalles de asignaturas automáticamente
            $this->crearDetallesAsignaturas($matricula);

            DB::commit();

            return back()->with('success', 'Matrícula aprobada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al aprobar la matrícula: ' . $e->getMessage());
        }
    }

    /**
     * Rechazar matrícula (solo admin/secretaria)
     */
    public function rechazar(Request $request, $codigo_matricula)
    {
        $validated = $request->validate([
            'motivo_rechazo' => 'required|string|max:500'
        ]);

        $matricula = Matricula::where('codigo_matricula', $codigo_matricula)->firstOrFail();
        
        if ($matricula->estudiante->activo) {
            return back()->with('error', 'Esta matrícula ya ha sido aprobada.');
        }

        DB::beginTransaction();
        
        try {
            // Eliminar relación estudiante-tutor
            DB::table('estudiantes_tutores')
              ->where('codigo_estudiante', $matricula->codigo_estudiante)
              ->delete();
            
            // Eliminar estudiante y matrícula
            $matricula->estudiante->delete();
            $matricula->delete();

            DB::commit();

            return back()->with('success', 'Matrícula rechazada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al rechazar la matrícula: ' . $e->getMessage());
        }
    }

    /**
     * Obtener grados por nivel educativo (AJAX)
     */
    public function obtenerGrados(Request $request)
    {
        $request->validate([
            'nivel_id' => 'required|integer|exists:niveles_educativos,nivel_educativo_id'
        ]);

        $nivelId = $request->get('nivel_id');
        
        $grados = Grado::where('nivel_educativo_id', $nivelId)
                      ->orderBy('grado')
                      ->get(['id_grado', 'grado']);
        
        return response()->json(['grados' => $grados]);
    }

    /**
     * Obtener secciones por grado (AJAX)
     */
    public function obtenerSecciones(Request $request)
    {
        $request->validate([
            'grado_id' => 'required|integer|exists:grados,id_grado'
        ]);

        $gradoId = $request->get('grado_id');
        
        $secciones = Seccion::where('grado_id', $gradoId)
                           ->get(['id_seccion', 'seccion']);
        
        return response()->json(['secciones' => $secciones]);
    }

    /**
     * Buscar estudiante por DNI (AJAX)
     */
    public function buscarEstudiante(Request $request)
    {
        $dni = $request->get('dni');
        
        $estudiante = Estudiante::where('dni', $dni)->first();
        
        if ($estudiante) {
            $ultimaMatricula = $estudiante->matriculas()->latest('fecha')->first();
            
            return response()->json([
                'found' => true,
                'estudiante' => [
                    'codigo_estudiante' => $estudiante->codigo_estudiante,
                    'nombre' => $estudiante->nombre,
                    'apellidos' => $estudiante->apellidos,
                    'dni' => $estudiante->dni,
                    'sexo' => $estudiante->sexo,
                    'fecha_nacimiento' => $estudiante->fecha_nacimiento,
                    'pais' => $estudiante->pais,
                    'provincia' => $estudiante->provincia,
                    'distrito' => $estudiante->distrito,
                    'departamento' => $estudiante->departamento,
                    'lengua_materna' => $estudiante->lengua_materna,
                    'religion' => $estudiante->religion,
                    'ultimo_grado' => $ultimaMatricula ? $ultimaMatricula->seccion->grado->nombre_completo : 'N/A'
                ]
            ]);
        }

        return response()->json(['found' => false]);
    }

    /**
     * Crear detalles de asignaturas para una matrícula
     */
    private function crearDetallesAsignaturas($matricula)
    {
        // Obtener el grado de la sección de la matrícula
        $grado = $matricula->seccion->grado;
        
        // Obtener todas las asignaturas del grado
        $asignaturas = Asignatura::where('grado_id', $grado->id_grado)->get();
        
        // Crear un detalle por cada asignatura
        foreach ($asignaturas as $asignatura) {
            DetalleAsignatura::create([
                'codigo_asignatura' => $asignatura->codigo_asignatura,
                'codigo_matricula' => $matricula->codigo_matricula,
                'fecha' => $matricula->fecha
            ]);
        }
    }

    /**
     * Generar código único para estudiante
     */
    private function generarCodigoEstudiante()
    {
        $anio = date('Y');
        $ultimoNumero = Estudiante::where('codigo_estudiante', 'like', "{$anio}%")
                                 ->whereNotNull('codigo_estudiante')
                                 ->count();
        $numero = str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);
        
        return "{$anio}{$numero}";
    }

    /**
     * Generar código único para matrícula
     */
    private function generarCodigoMatricula()
    {
        $anio = date('Y');
        $ultimoNumero = Matricula::where('codigo_matricula', 'like', "MAT{$anio}%")
                                ->whereNotNull('codigo_matricula')
                                ->count();
        $numero = str_pad($ultimoNumero + 1, 4, '0', STR_PAD_LEFT);
        
        return "MAT{$anio}{$numero}";
    }
}