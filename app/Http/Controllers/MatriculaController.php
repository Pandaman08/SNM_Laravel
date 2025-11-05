<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Matricula;
use App\Models\TipoMatricula;
use App\Models\AnioEscolar;
use App\Models\Tutor;
use App\Models\Estudiante;
use App\Models\NivelEducativo;
use App\Models\Grado;
use App\Models\Pago;
use App\Models\Seccion;
use App\Models\Persona;
use App\Models\Asignatura;
use App\Models\DetalleAsignatura;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\AsignaturaDocente;
use App\Models\InstitucionEducativa; // <-- agregado
use App\Models\ParienteTutor; // <-- agregado


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
        $currentYear = Carbon::now()->year;
        $anioActual = AnioEscolar::where('anio', $currentYear)->first();
        $tutores = Tutor::with('user')->get();
        // Obtener solo niveles educativos activos
        $nivelesEducativos = NivelEducativo::where('estado', true)->get();

        // Solo secciones con vacantes disponibles (estado_vacantes = true y matriculados < vacantes_seccion)
        $secciones = Seccion::with('grado.nivelEducativo')
            ->withCount([
                'matriculas as activos_count' => function ($q) {
                    $q->where('estado', 'activo');
                }
            ])
            ->get()
            ->filter(function ($s) {
                // Si vacantes_seccion es null, considerarlo disponible; además revisar estado_vacantes
                $hasVacantes = is_null($s->vacantes_seccion) ? true : ($s->activos_count < $s->vacantes_seccion);
                return ($s->estado_vacantes ?? true) && $hasVacantes;
            })
            ->values();

        return view('pages.admin.matriculas.create', compact(
            'tiposMatricula',
            'anioActual',
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
        $currentYear = Carbon::now()->year;
        $anioActual = AnioEscolar::where('anio', $currentYear)->first();
        // Obtener solo niveles educativos activos
        $nivelesEducativos = NivelEducativo::where('estado', true)->get();
        $grados = Grado::with('nivelEducativo')
            ->orderBy('grado')
            ->get(['id_grado', 'grado', 'nivel_educativo_id']);

        $secciones = Seccion::with('grado.nivelEducativo')
            ->withCount([
                'matriculas as activos_count' => function ($q) {
                    $q->where('estado', 'activo');
                }
            ])
            ->orderBy('seccion')
            ->get(['id_seccion', 'seccion', 'id_grado', 'vacantes_seccion', 'estado_vacantes'])
            ->filter(function ($s) {
                $hasVacantes = is_null($s->vacantes_seccion) ? true : ($s->activos_count < $s->vacantes_seccion);
                return ($s->estado_vacantes ?? true) && $hasVacantes;
            })
            ->values();

        return view('pages.admin.matriculas.create-tutor', compact(
            'tiposMatricula',
            'anioActual',
            'nivelesEducativos',
            'grados',
            'secciones'
        ));
    }

    public function buscarEstudiante(Request $request)
    {
        try {
            $request->validate([
                'dni' => 'required|string|size:8',
            ]);

            $dni = $request->input('dni');

            // Buscar estudiante a través de la relación con Persona
            $estudiante = Estudiante::with('persona')
                ->whereHas('persona', function ($query) use ($dni) {
                    $query->where('dni', $dni);
                })->first();

            if (!$estudiante) {
                return response()->json([
                    'found' => false,
                    'success' => true,
                    'message' => 'No se encontró ningún estudiante con ese DNI.'
                ]);
            }

            return response()->json([
                'found' => true,
                'success' => true,
                'estudiante' => [
                    'nombre' => $estudiante->persona->name,
                    'apellidos' => $estudiante->persona->lastname,
                    'dni' => $estudiante->persona->dni,
                    'sexo' => $estudiante->persona->sexo,
                    'fecha_nacimiento' => Carbon::parse($estudiante->persona->fecha_nacimiento)->format('Y-m-d'),
                    'pais' => $estudiante->pais,
                    'provincia' => $estudiante->provincia,
                    'distrito' => $estudiante->distrito,
                    'departamento' => $estudiante->departamento,
                    'lengua_materna' => $estudiante->lengua_materna,
                    'religion' => $estudiante->religion,
                    'address' => $estudiante->persona->address
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error en buscarEstudiante', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    /**
     * Guardar matrícula desde admin/secretaria
     */
    /**
     * Guardar solicitud de matrícula desde tutor
     */
    public function store(Request $request)
    {
        $baseValidationRules = [
            'institucion_educativa_codigo_modular' => 'nullable|string|exists:institucion_educativa,codigo_modular',
            'id_tipo_matricula' => 'required|exists:tipos_matricula,id_tipo_matricula',
            'id_anio_escolar' => 'required|exists:anios_escolares,id_anio_escolar',
            'seccion_id' => 'required|exists:secciones,id_seccion',
            'fecha' => 'required|date',
            'tutor_id' => 'required|exists:tutores,id_tutor',
            'tipo_relacion' => 'required|string|max:50',
        ];

        $newStudentValidationRules = [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:personas,dni',
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'pais' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'distrito' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'lengua_materna' => 'required|string|max:50',
            'religion' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
        ];

        $pagoValidationRules = [
            'concepto' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0',
            'fecha_pago' => 'required|date',
            'comprobante_img' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
        ];

        DB::beginTransaction();

        try {
            $validatedData = $request->validate($baseValidationRules);

            // Verificar disponibilidad de la sección antes de crear matrícula
            $seccion = Seccion::withCount([
                'matriculas as activos_count' => function ($q) {
                    $q->where('estado', 'activo');
                }
            ])->findOrFail($validatedData['seccion_id']);

            $activos = $seccion->activos_count;
            $vacantes = $seccion->vacantes_seccion;

            if (!($seccion->estado_vacantes ?? true) || (!is_null($vacantes) && $activos >= $vacantes)) {
                return back()->withErrors([
                    'seccion_id' => 'La sección seleccionada no tiene vacantes disponibles.'
                ])->withInput();
            }

            $estudiante = null;
            $codigoEstudiante = null;

            if (in_array($request->id_tipo_matricula, ['2'])) {

                $request->validate([
                    'dni_busqueda' => 'required|string|size:8|exists:personas,dni',
                ]);

                $persona = Persona::where('dni', $request->dni_busqueda)->firstOrFail();
                $estudiante = Estudiante::where('persona_id', $persona->persona_id)->firstOrFail();
                if (empty($estudiante->codigo_estudiante)) {
                    throw new \Exception('El estudiante encontrado no tiene un código válido');
                }
            } else {

                $validatedData = array_merge(
                    $validatedData,
                    $request->validate($newStudentValidationRules)
                );

                $persona = Persona::create([
                    'name' => $validatedData['nombre'],
                    'lastname' => $validatedData['apellidos'],
                    'dni' => $validatedData['dni'],
                    'sexo' => $validatedData['sexo'],
                    'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
                    'address' => $validatedData['address'] ?? null,
                ]);

                $codigoEstudiante = Estudiante::generarCodigoEstudiante();

                $estudiante = Estudiante::create([
                    'codigo_estudiante' => $codigoEstudiante,
                    'persona_id' => $persona->persona_id,
                    'pais' => $validatedData['pais'],
                    'provincia' => $validatedData['provincia'],
                    'distrito' => $validatedData['distrito'],
                    'departamento' => $validatedData['departamento'],
                    'lengua_materna' => $validatedData['lengua_materna'],
                    'religion' => $validatedData['religion'] ?? null,
                ]);
            }

            $estadoMat = !Auth::user()->isTutor() ? 'activo' : 'pendiente';
            $codigoEstudiante = $estudiante->codigo_estudiante;

            // Si el usuario es admin y la matrícula va a quedar 'activo', volver a verificar capacidad
            if ($estadoMat === 'activo') {
                // refrescar conteo
                $seccion->refresh();
                $activos = $seccion->matriculas()->where('estado', 'activo')->count();
                $vacantes = $seccion->vacantes_seccion;
                if (!($seccion->estado_vacantes ?? true) || (!is_null($vacantes) && $activos >= $vacantes)) {
                    return back()->withErrors([
                        'seccion_id' => 'No hay vacantes disponibles para activar la matrícula.'
                    ])->withInput();
                }
            }

            // obtener código modular de la institución (automático)
            $institucionCodigo = $this->resolverCodigoInstitucion();
            if (empty($institucionCodigo)) {
                return back()->withErrors([
                    'institucion_educativa_codigo_modular' => 'No se encontró la institución educativa en el sistema.'
                ])->withInput();
            }

            // Create matriculation
            $matricula = Matricula::create([
                'institucion_educativa_codigo_modular' => $institucionCodigo,
                'codigo_matricula' => Matricula::generarCodigoMatricula(),
                'codigo_estudiante' => $codigoEstudiante,
                'id_tipo_matricula' => $validatedData['id_tipo_matricula'],
                'id_anio_escolar' => $validatedData['id_anio_escolar'],
                'seccion_id' => $validatedData['seccion_id'],
                'fecha' => $validatedData['fecha'],
                'estado' => $estadoMat,
            ]);

            if (!Auth::user()->isTutor()) {
                $validatedPago = $request->validate($pagoValidationRules);
                $rutaImagen = null;
                if ($request->hasFile('comprobante_img')) {
                    $rutaImagen = $request->file('comprobante_img')->store('comprobantes', 'public');
                }

                $pago = Pago::create([
                    'codigo_matricula' => $matricula->codigo_matricula,
                    'concepto' => $validatedPago['concepto'],
                    'monto' => $validatedPago['monto'],
                    'fecha_pago' => $validatedPago['fecha_pago'],
                    'comprobante_img' => $rutaImagen,
                    'estado' => 'Finalizado'
                ]);
            }

            DB::table('estudiantes_tutores')->insert([
                'codigo_estudiante' => $codigoEstudiante,
                'id_tutor' => $validatedData['tutor_id'],
                'tipo_relacion' => $validatedData['tipo_relacion'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

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
    // |unique:personas,dni'
    public function storeTutor(Request $request)
    {
        $baseValidationRules = [
            'id_tipo_matricula' => 'required|exists:tipos_matricula,id_tipo_matricula',
            'id_anio_escolar' => 'required|exists:anios_escolares,id_anio_escolar',
            'seccion_id' => 'required|exists:secciones,id_seccion',
            'fecha' => 'required|date',
            'tipo_relacion' => 'required|string|max:50',
            // Parientes opcionales
            'parientes' => 'nullable|array',
            'parientes.*.nombre' => 'nullable|string|max:255',
            'parientes.*.celular' => 'nullable|digits:9',
        ];

        $newStudentValidationRules = [
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'dni' => 'required|string|size:8|unique:personas,dni',
            'sexo' => 'required|in:M,F',
            'fecha_nacimiento' => 'required|date',
            'pais' => 'required|string|max:100',
            'provincia' => 'required|string|max:100',
            'distrito' => 'required|string|max:100',
            'departamento' => 'required|string|max:100',
            'lengua_materna' => 'required|string|max:50',
            'religion' => 'nullable|string|max:50',
            'address' => 'required|string|max:255',
        ];

        $regularStudentValidationRules = [
            'dni_busqueda' => 'required|string|size:8|exists:personas,dni',
        ];

        \Log::info("Iniciando proceso de solicitud de matrícula por tutor", [
            'tutor_id' => Auth::user()->tutor->id_tutor,
            'id_tipo_matricula' => $request->id_tipo_matricula
        ]);

        DB::beginTransaction();

        try {
            $validatedData = $request->validate($baseValidationRules);

            $estudiante = null;
            $codigoEstudiante = null;

            // Verificar disponibilidad de la sección antes de crear matrícula
            $seccion = Seccion::withCount([
                'matriculas as activos_count' => function ($q) {
                    $q->where('estado', 'activo');
                }
            ])->findOrFail($validatedData['seccion_id']);

            $activos = $seccion->activos_count;
            $vacantes = $seccion->vacantes_seccion;

            if (!($seccion->estado_vacantes ?? true) || (!is_null($vacantes) && $activos >= $vacantes)) {
                return back()->withErrors([
                    'seccion_id' => 'La sección seleccionada no tiene vacantes disponibles.'
                ])->withInput();
            }

            // Lógica para matrícula regular (tipos 2, 3, 4)
            if (in_array($request->id_tipo_matricula, ['2', '3', '4'])) {

                $validatedData = array_merge(
                    $validatedData,
                    $request->validate($regularStudentValidationRules)
                );

                $persona = Persona::where('dni', $validatedData['dni_busqueda'])->firstOrFail();
                $estudiante = Estudiante::where('persona_id', $persona->persona_id)->firstOrFail();

                if (empty($estudiante->codigo_estudiante)) {
                    throw new \Exception('El estudiante encontrado no tiene un código válido');
                }

                $codigoEstudiante = $estudiante->codigo_estudiante;

            } else {
                // Lógica para nuevo ingreso (tipo 1)
                $validatedData = array_merge(
                    $validatedData,
                    $request->validate($newStudentValidationRules)
                );

                $persona = Persona::create([
                    'name' => $validatedData['nombre'],
                    'lastname' => $validatedData['apellidos'],
                    'dni' => $validatedData['dni'],
                    'sexo' => $validatedData['sexo'],
                    'fecha_nacimiento' => $validatedData['fecha_nacimiento'],
                    'address' => $validatedData['address']
                ]);

                $codigoEstudiante = Estudiante::generarCodigoEstudiante();

                $estudiante = Estudiante::create([
                    'codigo_estudiante' => $codigoEstudiante,
                    'persona_id' => $persona->persona_id,
                    'pais' => $validatedData['pais'],
                    'provincia' => $validatedData['provincia'],
                    'distrito' => $validatedData['distrito'],
                    'departamento' => $validatedData['departamento'],
                    'lengua_materna' => $validatedData['lengua_materna'],
                    'religion' => $validatedData['religion'] ?? null,
                ]);
            }

            // Obtener código modular de la institución
            $institucionCodigo = $this->resolverCodigoInstitucion();
            if (empty($institucionCodigo)) {
                return back()->withErrors([
                    'institucion_educativa_codigo_modular' => 'No se encontró la institución educativa en el sistema.'
                ])->withInput();
            }

            // Crear matrícula en estado pendiente
            $matricula = Matricula::create([
                'institucion_educativa_codigo_modular' => $institucionCodigo,
                'codigo_matricula' => Matricula::generarCodigoMatricula(),
                'codigo_estudiante' => $codigoEstudiante,
                'id_tipo_matricula' => $validatedData['id_tipo_matricula'],
                'id_anio_escolar' => $validatedData['id_anio_escolar'],
                'seccion_id' => $validatedData['seccion_id'],
                'fecha' => $validatedData['fecha'],
                'estado' => 'pendiente', // Siempre pendiente para solicitudes de tutor
            ]);

            // Actualizar si existe, insertar si no existe
            DB::table('estudiantes_tutores')->updateOrInsert(
                [
                    'codigo_estudiante' => $codigoEstudiante,
                    'id_tutor' => Auth::user()->tutor->id_tutor
                ],
                [
                    'tipo_relacion' => $validatedData['tipo_relacion'],
                    'updated_at' => now()
                ]
            );

            // ✅ FILTRAR PARIENTES VACÍOS ANTES DE GUARDAR
            \Log::info("parientes data:", $validatedData['parientes'] ?? []);

            if (!empty($validatedData['parientes']) && is_array($validatedData['parientes'])) {
                $tutor = Auth::user()->tutor;

                if ($tutor) {
                    // Filtrar parientes que tengan tanto nombre como celular completados
                    $parientesValidos = array_filter($validatedData['parientes'], function ($p) {
                        return !empty($p['nombre']) && !empty($p['celular']);
                    });

                    \Log::info("Parientes válidos filtrados:", [
                        'total_recibidos' => count($validatedData['parientes']),
                        'total_validos' => count($parientesValidos),
                        'parientes_validos' => $parientesValidos
                    ]);

                    // Solo guardar si hay parientes válidos
                    if (!empty($parientesValidos)) {
                        foreach ($parientesValidos as $p) {
                            ParienteTutor::create([
                                'tutor_id_tutor' => $tutor->id_tutor,
                                'nombre_pariente_tutor' => trim($p['nombre']),
                                'celular_pariente_tutor' => trim($p['celular']),
                            ]);
                        }

                        \Log::info("Parientes guardados correctamente", [
                            'tutor_id' => $tutor->id_tutor,
                            'cantidad' => count($parientesValidos)
                        ]);
                    } else {
                        \Log::info("No se encontraron parientes válidos para guardar");
                    }
                }
            } else {
                \Log::info("No se recibieron datos de parientes o el array está vacío");
            }

            DB::commit();

            $tipoMatricula = in_array($request->id_tipo_matricula, ['2', '3', '4']) ? 'regular' : 'nuevo ingreso';

            return redirect()->route('matriculas.mis-matriculas')
                ->with('success', "Solicitud de matrícula de {$tipoMatricula} enviada exitosamente. Será revisada por la administración.");

        } catch (\Exception $e) {
            DB::rollback();
            \Log::error("Error al procesar matrícula", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Error al enviar la solicitud: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Ver matrículas del tutor logueado
     */
    public function misMatriculas(Request $request)
    {
        $buscarpor = $request->input('buscarpor');
        $matriculas = Matricula::whereHas('estudiante', function ($query) {
            $query->whereHas('tutores', function ($q) {
                $q->where('tutores.id_tutor', Auth::user()->tutor->id_tutor);
            });
        })->with([
                    'estudiante',
                    'tipoMatricula',
                    'anioEscolar',
                    'seccion.grado.nivelEducativo'
                ])->orderBy('fecha', 'desc')->paginate(10);

        return view('pages.admin.tutor.matriculas', compact('matriculas', 'buscarpor'));
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
        ])->where('codigo_matricula', $codigo_matricula)->firstOrFail();

        return view('pages.admin.matriculas.show', compact('matricula'));
    }

    public function obtenerGrados(Request $request)
    {
        try {
            $request->validate([
                'nivel_id' => 'required|integer|exists:niveles_educativos,id_nivel_educativo'
            ]);

            $nivelId = $request->get('nivel_id');

            $grados = Grado::where('nivel_educativo_id', $nivelId)
                ->orderBy('grado')
                ->get(['id_grado', 'grado']);

            return response()->json([
                'success' => true,
                'grados' => $grados
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al obtener grados: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener los grados',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener secciones por grado (AJAX)
     */
    public function obtenerSecciones(Request $request)
    {

        try {
            $request->validate([
                'grado_id' => 'required|integer|exists:grados,id_grado'
            ]);

            $gradoId = $request->get('grado_id');

            // Buscar secciones usando conteo de matriculas activas y filtrando por vacantes
            $secciones = Seccion::where('id_grado', $gradoId)
                ->withCount([
                    'matriculas as activos_count' => function ($q) {
                        $q->where('estado', 'activo');
                    }
                ])
                ->orderBy('seccion')
                ->get(['id_seccion', 'seccion', 'vacantes_seccion', 'estado_vacantes'])
                ->filter(function ($s) {
                    $hasVacantes = is_null($s->vacantes_seccion) ? true : ($s->activos_count < $s->vacantes_seccion);
                    return ($s->estado_vacantes ?? true) && $hasVacantes;
                })
                ->values();

            if ($secciones->isEmpty()) {
                $totalSecciones = \App\Models\Seccion::count();
                $gradoExiste = \App\Models\Grado::find($gradoId);

                return response()->json([
                    'success' => true,
                    'secciones' => [],
                    'message' => 'No hay secciones disponibles para este grado',
                    'debug' => [
                        'grado_id' => $gradoId,
                        'total_secciones_bd' => $totalSecciones,
                        'grado_existe' => $gradoExiste ? true : false
                    ]
                ]);
            }

            $response = [
                'success' => true,
                'secciones' => $secciones,
                'grado_id' => $gradoId,
                'count' => $secciones->count()
            ];

            return response()->json($response);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error en obtenerSecciones', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerEstudiante(Request $request)
    {

        try {
            $request->validate([
                'dni_busqueda' => 'string|max:8'
            ]);

            $dni = $request->get('dni_busqueda');


            // Buscar estudiante
            $estudiante = Estudiante::whereHas('persona', function ($q) use ($dni) {
                $q->where('dni', 'like', '%' . $dni . '%');
            })->first();


            if (!$estudiante) {



                return response()->json([
                    'success' => true,
                    'message' => 'Alumno no encontrado',

                ]);
            }

            $response = [
                'success' => true,
                'estudiante' => $estudiante,
                'info' => Persona::find($estudiante->persona_id),

            ];

            return response()->json($response);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error de validación',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Error en obtener estudiante', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Error interno del servidor',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear detalles de asignaturas para una matrícula
     */
    private function crearDetallesAsignaturas($matricula)
    {
        // Obtener el grado de la sección de la matrícula
        $grado = $matricula->seccion->grado;

        // Obtener todas las asignaturas del grado
        $asignaturas = Asignatura::where('id_grado', $grado->id_grado)->get();

        // Crear un detalle por cada asignatura
        foreach ($asignaturas as $asignatura) {
            DetalleAsignatura::create([
                'codigo_asignatura' => $asignatura->codigo_asignatura,
                'codigo_matricula' => $matricula->codigo_matricula,
                'fecha' => $matricula->fecha
            ]);
        }
    }


    public function generarFicha($codigo_matricula)
    {
        $matricula = Matricula::with([
            'estudiante.persona',
            'anioEscolar',
            'tipoMatricula',
            'seccion.grado.nivelEducativo',
            'pagos' => function ($query) {
                $query->where('estado', 'Finalizado')->latest();
            }
        ])->findOrFail($codigo_matricula);

        $detalles = $matricula->seccion->grado->asignaturas->flatMap(function ($asignatura) {
            return $asignatura->asignaturasDocente;
        });



        // Verificar que la matrícula esté validada y tenga pagos finalizados
        if ($matricula->estado == 'pendiente' || $matricula->pagos->isEmpty()) {
            return back()->with('error', 'La matrícula no está validada o no tiene pagos finalizados');
        }


        $pdf = Pdf::loadView('pages.admin.matriculas.ficha-matricula', compact('matricula', 'detalles'));

        return $pdf->download('ficha-matricula-' . $matricula->codigo_matricula . '.pdf');
    }
    /**
     * Generar código único para matrícula
     */


    public function edit($codigo_matricula)
    {
        $matricula = Matricula::with(['estudiante.persona', 'seccion.grado'])->where('codigo_matricula', $codigo_matricula)->firstOrFail();
        $aniosEscolares = AnioEscolar::all();
        $tiposMatricula = TipoMatricula::all();
        $secciones = Seccion::with('grado')->get();

        return view('pages.admin.matriculas.edit', compact('matricula', 'aniosEscolares', 'tiposMatricula', 'secciones'));
    }


    public function update(Request $request, $codigo_matricula)
    {
        $request->validate([
            'id_tipo_matricula' => 'required|exists:tipos_matricula,id_tipo_matricula',
            'id_anio_escolar' => 'required|exists:anios_escolares,id_anio_escolar',
            'seccion_id' => 'required|exists:secciones,id_seccion',
            'estado' => 'required|in:pendiente,activo,rechazado,finalizado',
        ]);

        try {
            $matricula = Matricula::where('codigo_matricula', $codigo_matricula)->firstOrFail();

            $matricula->update([
                'id_tipo_matricula' => $request->id_tipo_matricula,
                'id_anio_escolar' => $request->id_anio_escolar,
                'seccion_id' => $request->seccion_id,
                'estado' => $request->estado,
            ]);

            return redirect()->route('matriculas.index', $codigo_matricula)
                ->with('success', '¡La matrícula se actualizó correctamente!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al actualizar la matrícula: ' . $e->getMessage());
        }
    }




    public function aprobar($codigo_matricula)
    {
        try {
            $matricula = Matricula::with('pagos', 'seccion')->findOrFail($codigo_matricula);

            // Verificar si hay pagos asociados
            if ($matricula->pagos->isEmpty()) {
                return back()->with('error', 'No se puede aprobar una matrícula sin pagos registrados');
            }

            // Verificar capacidad de la sección antes de aprobar
            $seccion = Seccion::withCount([
                'matriculas as activos_count' => function ($q) {
                    $q->where('estado', 'activo');
                }
            ])->findOrFail($matricula->seccion_id);

            $activos = $seccion->activos_count;
            $vacantes = $seccion->vacantes_seccion;

            if (!($seccion->estado_vacantes ?? true) || (!is_null($vacantes) && $activos >= $vacantes)) {
                return back()->with('error', 'No hay vacantes disponibles en la sección para aprobar esta matrícula');
            }

            // Actualizar estado de la matrícula
            $matricula->update([
                'estado' => 'activo',
                'motivo_rechazo' => null // Limpiar motivo de rechazo si existía
            ]);

            // Actualizar el último pago a estado "Finalizado"
            $ultimoPago = $matricula->pagos->sortByDesc('created_at')->first();
            $ultimoPago->update(['estado' => 'Finalizado']);

            foreach ($matricula->seccion->grado->asignaturas as $asignatura) {
                foreach ($asignatura->competencias as $competencia) {
                    DetalleAsignatura::create([
                        'id_competencias' => $competencia->id_competencias,
                        'codigo_matricula' => $matricula->codigo_matricula,
                        'fecha' => now()
                    ]);
                }
            }

            return back()->with('success', 'Matrícula aprobada exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al aprobar la matrícula: ' . $e->getMessage());
        }
    }




    public function rechazar(Request $request, $codigo_matricula)
    {
        $request->validate([
            'motivo_rechazo' => 'required|string|max:500'
        ]);

        try {
            $matricula = Matricula::with('pagos')->findOrFail($codigo_matricula);

            // Actualizar estado de la matrícula
            $matricula->update([
                'estado' => 'rechazado',
                'motivo_rechazo' => $request->motivo_rechazo
            ]);

            // Si hay pagos, actualizar el último a estado "Rechazado"
            if ($matricula->pagos->isNotEmpty()) {
                $ultimoPago = $matricula->pagos->sortByDesc('created_at')->first();
                $ultimoPago->update(['estado' => 'Rechazado']);
            }

            // Opcional: Desactivar al estudiante si es necesario


            return back()->with('success', 'Matrícula rechazada exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al rechazar la matrícula: ' . $e->getMessage());
        }
    }



    // Aceptar matrícula (cambiar estado a 'activo')

    public function destroy($codigo_matricula)
    {
        $matricula = Matricula::where('codigo_matricula', $codigo_matricula)->firstOrFail();
        $matricula->delete();

        return redirect()->route('matriculas.reporte')->with('success', 'Matrícula eliminada.');

    }

    public function reporte()
    {
        // Traemos las matrículas con relaciones
        $matriculas = Matricula::with(['estudiante.persona', 'seccion.grado.nivelEducativo'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Agrupamos por estado (activo, pendiente, inactivo)
        $estadisticas = $matriculas->groupBy('estado')->map->count();

        ##dd($estadisticas, $matriculas);


        return view('pages.admin.matriculas.reporte', [
            'matriculas' => $matriculas,
            'estadisticas' => $estadisticas
        ]);
    }

    // Resolver código modular de la única institución registrada (estática)
    protected function resolverCodigoInstitucion()
    {
        $ie = InstitucionEducativa::first();
        return $ie ? $ie->codigo_modular : null;
    }
}
