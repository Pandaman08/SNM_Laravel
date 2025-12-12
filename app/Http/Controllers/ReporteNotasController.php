<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\CalificacionFinal;
use App\Models\Competencia;
use Illuminate\Http\Request;
use App\Models\ReporteNota;
use App\Models\DetalleAsignatura;
use App\Models\Periodo;
use App\Models\Matricula;
use App\Models\EstudianteTutor;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteNotasController extends Controller
{
    public function showAsignaturas(Matricula $matricula)
    {
        $periodos = Periodo::orderBy('numero_periodo')->get();
        $periodoActual = Periodo::where('estado', 'Proceso')->first();

        $detalles = $this->getDetallesAsignaturas($matricula, $periodoActual?->id_periodo);

        return view('asignaturas.index', [
            'matricula' => $matricula,
            'periodos' => $periodos,
            'periodoSeleccionado' => $periodoActual,
            'detalles' => $detalles
        ]);
    }

    public function filtrarAsignaturas(Request $request, Matricula $matricula)
    {
        $request->validate([
            'periodo_id' => 'required|exists:periodos,id_periodo'
        ]);

        $detalles = $this->getDetallesAsignaturas($matricula, $request->periodo_id);

        return view('asignaturas.index', [
            'matricula' => $matricula,
            'periodos' => Periodo::orderBy('numero_periodo')->get(),
            'periodoSeleccionado' => Periodo::find($request->periodo_id),
            'detalles' => $detalles
        ]);
    }

    private function getDetallesAsignaturas($matricula, $periodoId = null)
    {
        if (!$periodoId)
            return collect();

        return DetalleAsignatura::with([
            'asignatura',
            'reportesNotas' => function ($query) use ($periodoId) {
                $query->where('id_periodo', $periodoId);
            }
        ])
            ->where('codigo_matricula', $matricula->codigo_matricula)
            ->get();
    }

    public function create($codigo_matricula, $id_asignatura)
    {
        $matricula = Matricula::findOrFail($codigo_matricula);
        $periodos = Periodo::all();

        $competencias = Competencia::with('detallesAsignatura')
            ->where('codigo_asignatura', $id_asignatura)
            ->get();

        // Flatten the collection of detalles_asignatura
        $detalles_asignatura = $competencias->flatMap(function ($competencia) use ($codigo_matricula) {
            return DetalleAsignatura::where('id_competencias', $competencia->id_competencias)
                ->where('codigo_matricula', $codigo_matricula)
                ->get();
        });

        \Log::info('detalle', $detalles_asignatura->toArray());

        return view('pages.admin.reporte_notas.create', compact('matricula', 'detalles_asignatura', 'periodos', 'id_asignatura'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_detalle_asignatura' => 'required|exists:detalles_asignatura,id_detalle_asignatura',
            'calificacion' => 'required|in:AD,A,B,C',
            'id_periodo' => 'required|exists:periodos,id_periodo',
            'observacion' => 'nullable|max:255',
            'fecha_registro' => 'required|date',
            'id_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
        ]);

        // Verificar que el periodo esté activo
        $periodo = Periodo::find($validated['id_periodo']);
        $hoy = now();

        if (!$hoy->between($periodo->fecha_inicio, $periodo->fecha_fin)) {
            return back()->with('error', 'No se puede registrar notas para este periodo en este momento');
        }

        // Verificar que no exista ya una nota para este detalle y periodo
        $existe = ReporteNota::where('id_detalle_asignatura', $validated['id_detalle_asignatura'])
            ->where('id_periodo', $validated['id_periodo'])
            ->exists();

        if ($existe) {
            return back()->with('error', 'Ya existe una nota registrada para este periodo');
        }

        ReporteNota::create([
            'id_detalle_asignatura' => $validated['id_detalle_asignatura'],
            'calificacion' => $validated['calificacion'],
            'id_periodo' => $validated['id_periodo'],
            'observacion' => $validated['observacion'],
            'fecha_registro' => $validated['fecha_registro'],
        ]);

        // Después de crear la nota, verificar si ya existen reportes para todos los periodos
        $periodos = Periodo::orderBy('id_periodo')->get();
        $periodoIds = $periodos->pluck('id_periodo')->toArray();

        $reportesCount = ReporteNota::where('id_detalle_asignatura', $validated['id_detalle_asignatura'])
            ->whereIn('id_periodo', $periodoIds)
            ->count();

        if ($reportesCount === count($periodoIds) && count($periodoIds) > 0) {
            // Calcular promedio y guardar en detalle_asignatura.calificacion_anual
            $reportes = ReporteNota::where('id_detalle_asignatura', $validated['id_detalle_asignatura'])
                ->whereIn('id_periodo', $periodoIds)
                ->get();

            $suma = 0; $valido = true;
            foreach ($reportes as $r) {
                $valor = $this->convertirNotaANumero($r->calificacion);
                if ($valor === null) { $valido = false; break; }
                $suma += $valor;
            }

            if ($valido) {
                $promedioLetra = $this->convertirNumeroANota($suma / count($periodoIds));
                \App\Models\DetalleAsignatura::where('id_detalle_asignatura', $validated['id_detalle_asignatura'])
                    ->update(['calificacion_anual' => $promedioLetra]);

                // Recalcular calificacion final de la asignatura para la matrícula
                $detalle = \App\Models\DetalleAsignatura::find($validated['id_detalle_asignatura']);
                if ($detalle && $detalle->asignatura) {
                    $this->recomputeCalificacionFinal($detalle->codigo_matricula, $detalle->asignatura->codigo_asignatura);
                }
            }
        }

        return back()->with('success', 'Nota registrada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'calificacion' => 'required|in:AD,A,B,C',
            'observacion' => 'nullable|max:255',
            'id_periodo' => 'required|exists:periodos,id_periodo',
        ]);

        // Verificar que el periodo esté activo
        $periodo = Periodo::find($validated['id_periodo']);
        $hoy = now();

        if (!$hoy->between($periodo->fecha_inicio, $periodo->fecha_fin)) {
            return back()->with('error', 'No se puede editar notas para este periodo en este momento');
        }

        $reporte = ReporteNota::findOrFail($id);
        $reporte->update([
            'calificacion' => $validated['calificacion'],
            'observacion' => $validated['observacion'],
        ]);

        // Después de actualizar la nota, verificar si ya existen reportes para todos los periodos
        $periodos = Periodo::orderBy('id_periodo')->get();
        $periodoIds = $periodos->pluck('id_periodo')->toArray();

        $detalleId = $reporte->id_detalle_asignatura;
        $reportesCount = ReporteNota::where('id_detalle_asignatura', $detalleId)
            ->whereIn('id_periodo', $periodoIds)
            ->count();

        if ($reportesCount === count($periodoIds) && count($periodoIds) > 0) {
            $reportes = ReporteNota::where('id_detalle_asignatura', $detalleId)
                ->whereIn('id_periodo', $periodoIds)
                ->get();

            $suma = 0; $valido = true;
            foreach ($reportes as $r) {
                $valor = $this->convertirNotaANumero($r->calificacion);
                if ($valor === null) { $valido = false; break; }
                $suma += $valor;
            }

            if ($valido) {
                $promedioLetra = $this->convertirNumeroANota($suma / count($periodoIds));
                \App\Models\DetalleAsignatura::where('id_detalle_asignatura', $detalleId)
                    ->update(['calificacion_anual' => $promedioLetra]);

                $detalle = \App\Models\DetalleAsignatura::find($detalleId);
                if ($detalle && $detalle->asignatura) {
                    $this->recomputeCalificacionFinal($detalle->codigo_matricula, $detalle->asignatura->codigo_asignatura);
                }
            }
        }

        return back()->with('success', 'Nota actualizada correctamente.');
    }

    public function getDetalles($id_asignatura)
    {
        $competencias = Competencia::where('codigo_asignatura', $id_asignatura)->get();
        $competenciaIds = $competencias->pluck('id_competencias');

        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)->get();
        $detalleIds = $detalles->pluck('id_detalle_asignatura');

        return ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->with([
                'detalleAsignatura' => function ($query) {
                    $query->select('*');
                },
                'periodo'
            ])
            ->get();
    }

    public function estudiante_calificaciones($codigo_matricula, $id_asignatura)
    {
        $matricula = Matricula::with(['estudiante.persona'])->findOrFail($codigo_matricula);
        $asignatura = Asignatura::findOrFail($id_asignatura);

        $competencias = Competencia::where('codigo_asignatura', $id_asignatura)->get();

        \Log::info('compes', $competencias->toArray());

        $competenciaIds = $competencias->pluck('id_competencias');
        \Log::info('compes', $competenciaIds->toArray());

        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)
            ->where('codigo_matricula', $codigo_matricula)
            ->get();
        \Log::info('DEATLLE', $detalles->toArray());

        $detalleIds = $detalles->pluck('id_detalle_asignatura');

        // Obtener periodos dinámicamente (todos los periodos registrados)
        $periodos = Periodo::orderBy('id_periodo')->get();
        $periodoIds = $periodos->pluck('id_periodo')->toArray();

        $hoy = now();
        $periodoActual = null;

        foreach ($periodos as $periodo) {
            if ($hoy->between($periodo->fecha_inicio, $periodo->fecha_fin)) {
                $periodoActual = $periodo;
                break;
            }
        }

        // Obtener todas las notas reportadas para estos detalles y periodos
        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', $periodoIds)
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        // Organizar los datos para la vista
        $competencias->each(function ($competencia) use ($detalles, $reportes, $periodoIds) {
            $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias);

            $competencia->detallesAsignatura->each(function ($detalle) use ($reportes, $periodoIds) {
                // Asignar reportes a cada detalle
                $detalle->reportesNotas = $reportes->get($detalle->id_detalle_asignatura, collect());

                    // Calcular promedio sólo si hay reportes para todos los periodos registrados
                    $periodosCount = count($periodoIds);
                    if ($detalle->reportesNotas && $detalle->reportesNotas->count() === $periodosCount && $periodosCount > 0) {
                        $suma = 0;
                        $valido = true;

                        foreach ($detalle->reportesNotas as $reporte) {
                            $valor = $this->convertirNotaANumero($reporte->calificacion);
                            if ($valor === null) {
                                $valido = false;
                                break;
                            }
                            $suma += $valor;
                        }

                        $detalle->promedio = $valido ? $this->convertirNumeroANota($suma / $periodosCount) : null;
                    } else {
                        $detalle->promedio = $detalle->calificacion_anual ?? null;
                    }
            });
        });

        return view('pages.admin.reporte_notas.estudiantes', compact(
            'matricula',
            'competencias',
            'asignatura',
            'periodos',
            'periodoActual'
        ));
    }

    public function index_estudiantes_tutor()
    {
        // Obtener el tutor autenticado
        $tutor = Auth::user()->tutor;

        if (!$tutor) {
            return redirect()->back()->with('error', 'No se encontró información de tutor');
        }

        // Obtener los estudiantes a cargo del tutor
        $estudiantesTutor = EstudianteTutor::where('id_tutor', $tutor->id_tutor)
            ->with([
                'estudiante.persona',
                'estudiante.matriculas' => function ($query) {
                    $query->whereHas('anioEscolar', function ($q) {
                        $q->where('anio', date('Y'));
                    })->with('seccion.grado');
                }
            ])
            ->get();



        $estudiantes = $estudiantesTutor->map(function ($item) {
            // Filtrar y luego obtener la primera matrícula activa del año actual
            $matriculaActiva = $item->estudiante->matriculas->filter(function ($matricula) {
                return $matricula->estado === 'activo' &&
                    $matricula->anioEscolar &&
                    $matricula->anioEscolar->anio == date('Y');
            })->first();

            return [
                'id_estudiante' => $item->estudiante->codigo_estudiante,
                'codigo_matricula' => $matriculaActiva ? $matriculaActiva->codigo_matricula : null,
                'nombre_completo' => $item->estudiante->persona->name . ' ' . $item->estudiante->persona->lastname,
                'dni' => $item->estudiante->persona->dni,
                'grado' => $matriculaActiva && $matriculaActiva->seccion ? $matriculaActiva->seccion->grado->grado : 'Sin matrícula activa',
                'seccion' => $matriculaActiva && $matriculaActiva->seccion ? $matriculaActiva->seccion->seccion : 'N/A',
                'tiene_matricula_activa' => (bool) $matriculaActiva,
                'estado_matricula' => $matriculaActiva ? $matriculaActiva->estado : 'sin_matricula'
            ];
        });

        return view('pages.admin.tutor.estudiantes', [
            'estudiantes' => $estudiantes,
            'tutor' => $tutor
        ]);
    }

    public function verNotasEstudiante($codigo_matricula)
    {
        $matricula = Matricula::with(['estudiante.persona', 'seccion.grado'])
            ->findOrFail($codigo_matricula);

        // Obtener todas las asignaturas del grado
        $asignaturas = Asignatura::where('id_grado', $matricula->seccion->grado->id_grado)
            ->orderBy('nombre')
            ->get();

        // Obtener IDs de todas las asignaturas
        $asignaturaIds = $asignaturas->pluck('codigo_asignatura');

        // Obtener todas las competencias de estas asignaturas
        $competencias = Competencia::whereIn('codigo_asignatura', $asignaturaIds)->get();
        $competenciaIds = $competencias->pluck('id_competencias');

        // Obtener detalles de asignatura para estas competencias y matrícula
        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)
            ->where('codigo_matricula', $codigo_matricula)
            ->get();
        $detalleIds = $detalles->pluck('id_detalle_asignatura');

        // Obtener periodos dinámicamente y los reportes correspondientes
        $periodos = Periodo::orderBy('id_periodo')->get();
        $periodoIds = $periodos->pluck('id_periodo')->toArray();

        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', $periodoIds)
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        $asignaturas->each(function ($asignatura) use ($competencias, $detalles, $reportes, $periodoIds) {
            $asignatura->competencias = $competencias->where('codigo_asignatura', $asignatura->codigo_asignatura);

            $asignatura->competencias->each(function ($competencia) use ($detalles, $reportes, $periodoIds) {
                $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias);

                $competencia->detallesAsignatura->each(function ($detalle) use ($reportes, $periodoIds) {
                    $detalle->reportesNotas = $reportes->get($detalle->id_detalle_asignatura, collect());

                    $periodosCount = count($periodoIds);
                    if ($detalle->reportesNotas->count() === $periodosCount && $periodosCount > 0) {
                        $suma = 0;
                        $valido = true;

                        foreach ($detalle->reportesNotas as $reporte) {
                            $valor = $this->convertirNotaANumero($reporte->calificacion ?? '');
                            if ($valor === null) {
                                $valido = false;
                                break;
                            }
                            $suma += $valor;
                        }

                        $detalle->promedio = $valido ? $this->convertirNumeroANota($suma / $periodosCount) : null;
                    } else {
                        $detalle->promedio = $detalle->calificacion_anual ?? null;
                    }
                });
            });
        });

        return view('pages.admin.reporte_notas.tutor-estudiantes', [
            'matricula' => $matricula,
            'asignaturas' => $asignaturas
        ]);
    }

    /**
     * Recalcula la calificación final de una asignatura para una matrícula
     */
    private function recomputeCalificacionFinal($codigo_matricula, $codigo_asignatura)
    {
        if (!$codigo_matricula || !$codigo_asignatura) return;

        // Obtener competencias de la asignatura
        $competencias = Competencia::where('codigo_asignatura', $codigo_asignatura)->get();
        $competenciaIds = $competencias->pluck('id_competencias')->toArray();

        // Obtener detalles para esa matrícula y esas competencias
        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)
            ->where('codigo_matricula', $codigo_matricula)
            ->get();

        if ($detalles->isEmpty()) {
            // Borrar si existe
            CalificacionFinal::where('codigo_matricula', $codigo_matricula)
                ->where('codigo_asignatura', $codigo_asignatura)
                ->delete();
            return;
        }

        // Reunir calificaciones anuales
        $valores = [];
        foreach ($detalles as $d) {
            if (!empty($d->calificacion_anual)) {
                $num = $this->convertirNotaANumero($d->calificacion_anual);
                if ($num !== null) $valores[] = $num;
            }
        }

        // Sólo calcular si tenemos calificaciones anuales para todas las competencias
        if (count($valores) === count($competenciaIds) && count($valores) > 0) {
            $suma = array_sum($valores);
            $promedio = $suma / count($valores);
            $promedioLetra = $this->convertirNumeroANota($promedio);

            CalificacionFinal::updateOrCreate(
                ['codigo_matricula' => $codigo_matricula, 'codigo_asignatura' => $codigo_asignatura],
                ['calificacion_final' => $promedioLetra, 'fecha_registro' => now()->toDateString()]
            );
        }
    }

    public function generarReportePdf($codigo_matricula)
    {
        // Obtener los mismos datos que en verNotasEstudiante
        $matricula = Matricula::with(['estudiante.persona', 'seccion.grado'])
            ->findOrFail($codigo_matricula);

        $asignaturas = Asignatura::where('id_grado', $matricula->seccion->grado->id_grado)
            ->orderBy('nombre')
            ->get();

        // Obtener IDs de todas las asignaturas
        $asignaturaIds = $asignaturas->pluck('codigo_asignatura');

        // Obtener todas las competencias de estas asignaturas
        $competencias = Competencia::whereIn('codigo_asignatura', $asignaturaIds)->get();
        $competenciaIds = $competencias->pluck('id_competencias');

        // Obtener detalles de asignatura para estas competencias y matrícula
        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)
            ->where('codigo_matricula', $codigo_matricula)
            ->get();
        $detalleIds = $detalles->pluck('id_detalle_asignatura');

        // Obtener periodos dinámicos y reportes correspondientes
        $periodos = Periodo::orderBy('id_periodo')->get();
        $periodoIds = $periodos->pluck('id_periodo')->toArray();

        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', $periodoIds)
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        $asignaturas->each(function ($asignatura) use ($competencias, $detalles, $reportes, $periodoIds) {
            $asignatura->competencias = $competencias->where('codigo_asignatura', $asignatura->codigo_asignatura);

            $asignatura->competencias->each(function ($competencia) use ($detalles, $reportes, $periodoIds) {
                $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias);

                $competencia->detallesAsignatura->each(function ($detalle) use ($reportes, $periodoIds) {
                    $detalle->reportesNotas = $reportes->get($detalle->id_detalle_asignatura, collect());

                    $periodosCount = count($periodoIds);
                    if ($detalle->reportesNotas->count() === $periodosCount && $periodosCount > 0) {
                        $suma = 0;
                        $valido = true;

                        foreach ($detalle->reportesNotas as $reporte) {
                            $valor = $this->convertirNotaANumero($reporte->calificacion ?? '');
                            if ($valor === null) {
                                $valido = false;
                                break;
                            }
                            $suma += $valor;
                        }

                        $detalle->promedio = $valido ? $this->convertirNumeroANota($suma / $periodosCount) : null;
                    } else {
                        $detalle->promedio = $detalle->calificacion_anual ?? null;
                    }
                });
            });
            // Cargar calificacion final por asignatura si existe
            $final = CalificacionFinal::where('codigo_matricula', $matricula->codigo_matricula ?? '')->where('codigo_asignatura', $asignatura->codigo_asignatura)->first();
            $asignatura->calificacion_final = $final->calificacion_final ?? null;
        });

        // Configurar PDF
        $pdf = Pdf::loadView('pages.admin.reporte_notas.reporte-pdf', [
            'matricula' => $matricula,
            'asignaturas' => $asignaturas,
            'fecha' => Carbon::parse(now())->format('d/m/Y')
        ]);

        return $pdf->download('reporte-calificaciones-' . $matricula->estudiante->persona->lastname . '.pdf');
    }

    private function convertirNotaANumero($nota)
    {
        $escala = [
            'AD' => 4,
            'A' => 3,
            'B' => 2,
            'C' => 1
        ];

        return $escala[$nota] ?? null;
    }

    private function convertirNumeroANota($numero)
    {
        $escala = [
            4 => 'AD',
            3 => 'A',
            2 => 'B',
            1 => 'C'
        ];

        return $escala[round($numero)] ?? '-';
    }

    public function docente_view($id_asignatura)
    {
        $asignatura = Asignatura::findOrFail($id_asignatura);

        $reportes = \DB::table('matriculas as m')->where('m.estado', '=', 'activo')
            ->join('estudiantes as e', 'm.codigo_estudiante', '=', 'e.codigo_estudiante')
            ->join('personas as p', 'e.persona_id', '=', 'p.persona_id')
            ->leftJoin('detalles_asignatura as da', function ($join) use ($id_asignatura) {
                $join->on('m.codigo_matricula', '=', 'da.codigo_matricula')
                    ->whereExists(function ($query) use ($id_asignatura) {
                        $query->select(\DB::raw(1))
                            ->from('competencias as c')
                            ->whereColumn('c.id_competencias', 'da.id_competencias')
                            ->where('c.codigo_asignatura', $id_asignatura);
                    });
            })
            ->leftJoin('reportes_notas as r', 'da.id_detalle_asignatura', '=', 'r.id_detalle_asignatura')
            ->leftJoin('periodos as per', 'r.id_periodo', '=', 'per.id_periodo')
            ->select(
                'p.persona_id',
                \DB::raw("CONCAT(p.name, ' ', p.lastname) as estudiante"),
                \DB::raw("MAX(CASE WHEN per.id_periodo = 1 THEN r.calificacion ELSE NULL END) as periodo1"),
                \DB::raw("MAX(CASE WHEN per.id_periodo = 2 THEN r.calificacion ELSE NULL END) as periodo2"),
                \DB::raw("MAX(CASE WHEN per.id_periodo = 3 THEN r.calificacion ELSE NULL END) as periodo3"),
            )
            ->groupBy('p.persona_id', 'p.name', 'p.lastname')
            ->orderBy('p.lastname')
            ->get();

        return view('pages.admin.reporte_notas.docentes-view', compact('reportes', 'asignatura'));
    }

    /**
     * Cargar vista masiva de calificaciones para una asignatura
     * Solo muestra el período activo
     */
    public function calificacionesMasivas($id_asignatura)
    {
        $asignatura = Asignatura::with('grado')->findOrFail($id_asignatura);

        // Obtener período activo
        $periodoActual = Periodo::where('estado', 'Proceso')->first();

        if (!$periodoActual) {
            return back()->with('error', 'No hay período activo en este momento');
        }

        // Obtener todas las matrículas activas para esta asignatura (por sus secciones)
        $secciones = $asignatura->grado->secciones;
        $seccionIds = $secciones->pluck('id_seccion')->toArray();

        $matriculas = Matricula::with(['estudiante.persona'])
            ->whereIn('seccion_id', $seccionIds)
            ->where('estado', 'activo')
            ->orderBy('codigo_matricula')
            ->get();

        // Obtener competencias de la asignatura
        $competencias = Competencia::where('codigo_asignatura', $id_asignatura)
            ->get();

        // Para cada matrícula, obtener los detalles y reportes de notas
        $competenciaIds = $competencias->pluck('id_competencias')->toArray();

        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)
            ->with([
                'reportesNotas' => function ($query) use ($periodoActual) {
                    $query->where('id_periodo', $periodoActual->id_periodo);
                }
            ])
            ->get()
            ->groupBy('codigo_matricula');

        // Verificar si ya existen notas registradas para este período
        $notasRegistradas = ReporteNota::whereIn('id_periodo', [$periodoActual->id_periodo])
            ->whereIn('id_detalle_asignatura', $detalles->flatten()->pluck('id_detalle_asignatura'))
            ->exists();

        return view('pages.admin.reporte_notas.calificaciones-masivas', [
            'asignatura' => $asignatura,
            'periodoActual' => $periodoActual,
            'matriculas' => $matriculas,
            'competencias' => $competencias,
            'detalles' => $detalles,
            'notasRegistradas' => $notasRegistradas
        ]);
    }

    /**
     * Vista de prueba: permitir al docente calificar todos los periodos para una asignatura
     * mostrando todos los estudiantes y periodos para completar (no verifica periodo activo).
     */
    public function calificarTodos($id_asignatura)
    {
        $asignatura = Asignatura::with('grado')->findOrFail($id_asignatura);

        // Obtener todas las matrículas activas para esta asignatura (por sus secciones)
        $secciones = $asignatura->grado->secciones;
        $seccionIds = $secciones->pluck('id_seccion')->toArray();

        $matriculas = Matricula::with(['estudiante.persona'])
            ->whereIn('seccion_id', $seccionIds)
            ->where('estado', 'activo')
            ->orderBy('codigo_matricula')
            ->get();

        // Obtener periodos (todos)
        $periodos = Periodo::orderBy('id_periodo')->get();

        // Obtener competencias y detalles
        $competencias = Competencia::where('codigo_asignatura', $id_asignatura)->get();
        $competenciaIds = $competencias->pluck('id_competencias')->toArray();

        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)
            ->whereIn('codigo_matricula', $matriculas->pluck('codigo_matricula')->toArray())
            ->with('reportesNotas')
            ->get()
            ->groupBy('codigo_matricula');

        return view('pages.admin.reporte_notas.calificar-todos', [
            'asignatura' => $asignatura,
            'matriculas' => $matriculas,
            'periodos' => $periodos,
            'competencias' => $competencias,
            'detalles' => $detalles,
        ]);
    }

    /**
     * Guardar calificaciones para varios periodos a la vez (testing).
     * Entrada: calificaciones[periodoId][id_detalle] = 'A' etc.
     */
    public function guardarCalificacionesMasivasAllPeriods(Request $request)
    {
        $id_asignatura = $request->input('id_asignatura');
        $calificaciones = $request->input('calificaciones', []); // [periodoId => [id_detalle => calificacion]]
        $observaciones = $request->input('observaciones', []); // similar structure

        try {
            $affectedDetalles = [];

            foreach ($calificaciones as $periodoId => $detallesArray) {
                foreach ($detallesArray as $id_detalle => $calificacion) {
                    if (!in_array($calificacion, ['AD', 'A', 'B', 'C'])) continue;

                    $observacion = $observaciones[$periodoId][$id_detalle] ?? null;
                    $observacion = !empty($observacion) ? $observacion : null;

                    $reporte = ReporteNota::where('id_detalle_asignatura', $id_detalle)
                        ->where('id_periodo', $periodoId)
                        ->first();

                    if ($reporte) {
                        $reporte->update([
                            'calificacion' => $calificacion,
                            'observacion' => $observacion,
                            'fecha_registro' => now()->toDateString(),
                        ]);
                    } else {
                        ReporteNota::create([
                            'id_detalle_asignatura' => $id_detalle,
                            'id_periodo' => $periodoId,
                            'calificacion' => $calificacion,
                            'observacion' => $observacion,
                            'fecha_registro' => now()->toDateString(),
                        ]);
                    }

                    $affectedDetalles[] = $id_detalle;
                }
            }

            // Recalcular promedio anual para los detalles afectados
            $periodos = Periodo::orderBy('id_periodo')->get();
            $periodoIds = $periodos->pluck('id_periodo')->toArray();

            $uniqueDetalles = array_values(array_unique($affectedDetalles));
            foreach ($uniqueDetalles as $detalleId) {
                $reportes = ReporteNota::where('id_detalle_asignatura', $detalleId)
                    ->whereIn('id_periodo', $periodoIds)
                    ->get();

                if ($reportes->count() === count($periodoIds) && count($periodoIds) > 0) {
                    $suma = 0; $valido = true;
                    foreach ($reportes as $r) {
                        $valor = $this->convertirNotaANumero($r->calificacion);
                        if ($valor === null) { $valido = false; break; }
                        $suma += $valor;
                    }

                    if ($valido) {
                        $promedioLetra = $this->convertirNumeroANota($suma / count($periodoIds));
                        \App\Models\DetalleAsignatura::where('id_detalle_asignatura', $detalleId)
                            ->update(['calificacion_anual' => $promedioLetra]);

                        // Recalcular la calificacion final por asignatura para esta matrícula
                        $detalle = \App\Models\DetalleAsignatura::find($detalleId);
                        if ($detalle && $detalle->asignatura) {
                            $this->recomputeCalificacionFinal($detalle->codigo_matricula, $detalle->asignatura->codigo_asignatura);
                        }
                    }
                }
            }

            return redirect()->back()->with('success', 'Calificaciones guardadas (multi-periodo).');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al guardar calificaciones: ' . $e->getMessage());
        }
    }

    /**
     * Guardar calificaciones en lote
     */
    public function guardarCalificacionesMasivas(Request $request)
    {
        $id_asignatura = $request->input('id_asignatura');
        $id_periodo = $request->input('id_periodo');
        $calificaciones = $request->input('calificaciones'); // Array: [id_detalle => calificacion]
        $observaciones = $request->input('observaciones', []); // Array: [id_detalle => observacion]

        $periodo = Periodo::findOrFail($id_periodo);

        // Validar que el período esté activo
        $hoy = now();
        if (!$hoy->between($periodo->fecha_inicio, $periodo->fecha_fin)) {
            return back()->with('error', 'No puedes registrar calificaciones fuera del período activo');
        }

        // Validar que no existan notas ya registradas
        $asignatura = Asignatura::findOrFail($id_asignatura);
        $competencias = Competencia::where('codigo_asignatura', $id_asignatura)->pluck('id_competencias');
        
        $existentes = DetalleAsignatura::whereIn('id_competencias', $competencias)
            ->whereHas('reportesNotas', function ($q) use ($id_periodo) {
                $q->where('id_periodo', $id_periodo);
            })
            ->exists();

        if ($existentes) {
            return back()->with('error', 'Ya existen calificaciones registradas para este período');
        }

        try {
            foreach ($calificaciones as $id_detalle => $calificacion) {
                // Validar que la calificación sea válida
                if (!in_array($calificacion, ['AD', 'A', 'B', 'C'])) {
                    continue; // Saltar si está vacía o inválida
                }

                // Obtener observación si existe
                $observacion = $observaciones[$id_detalle] ?? null;
                $observacion = !empty($observacion) ? $observacion : null;

                // Crear la nota
                ReporteNota::create([
                    'id_detalle_asignatura' => $id_detalle,
                    'id_periodo' => $id_periodo,
                    'calificacion' => $calificacion,
                    'fecha_registro' => now()->toDateString(),
                    'observacion' => $observacion
                ]);
            }

            return redirect()->to(url()->previous())->with([
                'success' => 'Calificaciones registradas correctamente',
                'redirect_to' => route('docentes.asignaturas')
            ]);
        } catch (\Exception $e) {
            return redirect()->to(url()->previous())->with([
                'error' => 'Error al registrar calificaciones: ' . $e->getMessage(),
                'redirect_to' => route('docentes.asignaturas')
            ]);
        }
    }

    /**
     * Actualizar calificaciones en lote
     */
    public function actualizarCalificacionesMasivas(Request $request)
    {
        $id_periodo = $request->input('id_periodo');
        $calificaciones = $request->input('calificaciones'); // Array: [id_reporte => calificacion]
        $observaciones = $request->input('observaciones', []); // Array: [id_reporte => observacion]

        $periodo = Periodo::findOrFail($id_periodo);

        // Validar que el período esté activo
        $hoy = now();
        if (!$hoy->between($periodo->fecha_inicio, $periodo->fecha_fin)) {
            return back()->with('error', 'No puedes editar calificaciones fuera del período activo');
        }

        try {
            foreach ($calificaciones as $id_reporte => $calificacion) {
                if (!in_array($calificacion, ['AD', 'A', 'B', 'C'])) {
                    continue;
                }

                $reporte = ReporteNota::findOrFail($id_reporte);
                
                // Obtener observación si existe
                $observacion = $observaciones[$id_reporte] ?? null;
                $observacion = !empty($observacion) ? $observacion : null;

                $reporte->update([
                    'calificacion' => $calificacion,
                    'observacion' => $observacion
                ]);
            }

            return redirect()->to(url()->previous())->with([
                'success' => 'Calificaciones actualizadas correctamente',
                'redirect_to' => route('docentes.asignaturas')
            ]);
        } catch (\Exception $e) {
            return redirect()->to(url()->previous())->with([
                'error' => 'Error al actualizar calificaciones: ' . $e->getMessage(),
                'redirect_to' => route('docentes.asignaturas')
            ]);
        }
    }
}