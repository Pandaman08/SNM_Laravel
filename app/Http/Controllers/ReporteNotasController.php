<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\CalificacionFinal;
use App\Models\Competencia;
use Illuminate\Http\Request;
use App\Models\ReporteNota;
use App\Models\DetalleAsignatura;
use App\Models\AnioEscolar;
use App\Models\Periodo;
use App\Models\Matricula;
use App\Models\EstudianteTutor;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ReporteNotasController extends Controller
{
    public function index()
    {
        // Redirigir a asignaturas asignadas del docente
        return redirect()->route('docentes.asignaturas');
    }

    public function showAsignaturas(Matricula $matricula)
    {
        $periodos = Periodo::orderBy('id_periodo')->get();
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
            'periodos' => Periodo::orderBy('id_periodo')->get(),
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
        $competenciaIds = $competencias->pluck('id_competencias');

        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)
            ->where('codigo_matricula', $codigo_matricula)
            ->get();
        $detalleIds = $detalles->pluck('id_detalle_asignatura');

        // Obtener el año académico de la matrícula
        $anioEscolarMatricula = $matricula->id_anio_escolar 
            ? AnioEscolar::find($matricula->id_anio_escolar)
            : AnioEscolar::where('estado', 'Activo')->first();
        
        // Obtener solo los periodos del año académico de la matrícula
        $periodos = $anioEscolarMatricula
            ? Periodo::where('id_anio_escolar', $anioEscolarMatricula->id_anio_escolar)->orderBy('id_periodo')->get()
            : collect();
        
        $periodoIds = $periodos->pluck('id_periodo')->toArray();
        
        $hoy = now();
        $periodoActual = null;

        foreach ($periodos as $periodo) {
            if ($hoy->between($periodo->fecha_inicio, $periodo->fecha_fin)) {
                $periodoActual = $periodo;
                break;
            }
        }

        // Obtener todas las notas reportadas para estos detalles (de los periodos del año académico)
        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', $periodoIds)
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        // Obtener el último período
        $ultimoPeriodo = $periodos->last();

        // Organizar los datos para la vista
        $competencias->each(function ($competencia) use ($detalles, $reportes, $periodos, $ultimoPeriodo) {
            $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias)->values();

            $competencia->detallesAsignatura->each(function ($detalle) use ($reportes, $periodos, $ultimoPeriodo) {
                // Asignar reportes a cada detalle
                $notasDelDetalle = $reportes->get($detalle->id_detalle_asignatura, collect());
                $detalle->reportesNotas = $notasDelDetalle;

                // Mostrar notas aunque sea parcial. Calcular promedio SOLO si están todos los periodos
                $totalPeriodos = $periodos->count();
                
                if ($notasDelDetalle->count() > 0) {
                    // Si tiene notas de TODOS los periodos, calcular promedio
                    if ($notasDelDetalle->count() === $totalPeriodos && $totalPeriodos > 0) {
                        $suma = 0;
                        $valido = true;

                        foreach ($notasDelDetalle as $reporte) {
                            $valor = $this->convertirNotaANumero($reporte->calificacion);
                            if ($valor === null) {
                                $valido = false;
                                break;
                            }
                            $suma += $valor;
                        }

                        $detalle->promedio = $valido ? $this->convertirNumeroANota($suma / $totalPeriodos) : null;
                    } else {
                        // Si no tiene todos los periodos, no mostrar promedio
                        $detalle->promedio = null;
                    }
                }
            });
        });

        // Adjuntar calificación final de la asignatura (si existe)
        $final = CalificacionFinal::where('codigo_matricula', $codigo_matricula)
            ->where('codigo_asignatura', $asignatura->codigo_asignatura)
            ->first();
        $asignatura->calificacion_final = $final->calificacion_final ?? null;

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

        // Obtener el año académico de la matrícula
        $anioEscolarMatricula = $matricula->id_anio_escolar 
            ? AnioEscolar::find($matricula->id_anio_escolar)
            : AnioEscolar::where('estado', 'Activo')->first();
        
        // Obtener solo los periodos del año académico de la matrícula
        $periodos = $anioEscolarMatricula
            ? Periodo::where('id_anio_escolar', $anioEscolarMatricula->id_anio_escolar)->orderBy('id_periodo')->get()
            : collect();

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

        // Obtener periodos ids del año académico
        $periodoIds = $periodos->pluck('id_periodo')->toArray();

        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', $periodoIds)
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        $asignaturas->each(function ($asignatura) use ($competencias, $detalles, $reportes, $periodoIds, $periodos) {
            $asignatura->competencias = $competencias->where('codigo_asignatura', $asignatura->codigo_asignatura);

            $asignatura->competencias->each(function ($competencia) use ($detalles, $reportes, $periodoIds, $periodos) {
                $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias);

                $competencia->detallesAsignatura->each(function ($detalle) use ($reportes, $periodoIds, $periodos) {
                    $detalle->reportesNotas = $reportes->get($detalle->id_detalle_asignatura, collect());

                    $totalPeriodos = count($periodoIds);
                    // Mostrar notas aunque sea parcial. Calcular promedio SOLO si están todos los periodos
                    if ($detalle->reportesNotas->count() > 0) {
                        // Si tiene notas de TODOS los periodos, calcular promedio
                        if ($detalle->reportesNotas->count() === $totalPeriodos && $totalPeriodos > 0) {
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

                            $detalle->promedio = $valido ? $this->convertirNumeroANota($suma / $totalPeriodos) : null;
                        } else {
                            // Si no tiene todos los periodos, no mostrar promedio
                            $detalle->promedio = null;
                        }
                    }
                });
            });
        });

        // Adjuntar calificaciones finales por asignatura
        $finales = CalificacionFinal::where('codigo_matricula', $codigo_matricula)->get()->keyBy('codigo_asignatura');
        foreach ($asignaturas as $asig) {
            $fila = $finales->get($asig->codigo_asignatura);
            $asig->calificacion_final = $fila->calificacion_final ?? null;
        }

        return view('pages.admin.reporte_notas.tutor-estudiantes', [
            'matricula' => $matricula,
            'asignaturas' => $asignaturas,
            'periodos' => $periodos
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

        // Obtener el año académico de la matrícula
        $anioEscolarMatricula = $matricula->id_anio_escolar 
            ? AnioEscolar::find($matricula->id_anio_escolar)
            : AnioEscolar::where('estado', 'Activo')->first();
        
        // Obtener solo los periodos del año académico de la matrícula
        $periodos = $anioEscolarMatricula
            ? Periodo::where('id_anio_escolar', $anioEscolarMatricula->id_anio_escolar)->orderBy('id_periodo')->get()
            : collect();
        
        $periodoIds = $periodos->pluck('id_periodo')->toArray();

        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', $periodoIds)
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        $asignaturas->each(function ($asignatura) use ($codigo_matricula, $competencias, $detalles, $reportes, $periodoIds) {
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
            $final = CalificacionFinal::where('codigo_matricula', $codigo_matricula)->where('codigo_asignatura', $asignatura->codigo_asignatura)->first();
            $asignatura->calificacion_final = $final->calificacion_final ?? null;
        });

        // Configurar PDF
        $pdf = Pdf::loadView('pages.admin.reporte_notas.reporte-pdf', [
            'matricula' => $matricula,
            'asignaturas' => $asignaturas,
            'periodos' => $periodos,
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
    /**
 * Mostrar vista de calificaciones masivas por competencia
 */
    public function calificacionesMasivas($id_asignatura)
    {
        $asignatura = Asignatura::with(['grado', 'competencias'])->findOrFail($id_asignatura);
        $anioEscolar = AnioEscolar::where('estado', 'Activo')->first();
        // Obtener todos los periodos
        $periodos = Periodo::where('id_anio_escolar', $anioEscolar->id_anio_escolar)
            ->orderBy('nombre')
            ->get();
        
        // Obtener las competencias de la asignatura
        $competencias = $asignatura->competencias;

        return view('pages.admin.reporte_notas.calificaciones-masivas', [
            'asignatura' => $asignatura,
            'periodos' => $periodos,
            'competencias' => $competencias
        ]);
    }


    /**
     * API: Obtener estudiantes y notas de una competencia específica
     */
    public function obtenerNotasCompetencia($id_asignatura, $id_competencia)
    {
        $asignatura = Asignatura::with('grado')->findOrFail($id_asignatura);
        $competencia = Competencia::findOrFail($id_competencia);

        // Validar que la competencia pertenece a la asignatura
        if ($competencia->codigo_asignatura != $id_asignatura) {
            return response()->json(['error' => 'Competencia no pertenece a esta asignatura'], 400);
        }

        // Obtener todas las matrículas activas para esta asignatura
        $secciones = $asignatura->grado->secciones;
        $seccionIds = $secciones->pluck('id_seccion')->toArray();

        $matriculas = Matricula::with(['estudiante.persona'])
            ->whereIn('seccion_id', $seccionIds)
            ->where('estado', 'activo')
            ->get()
            ->sortBy(function($matricula) {
                return $matricula->estudiante->persona->lastname . ' ' . $matricula->estudiante->persona->name;
            })
            ->values(); // Reset keys after sorting

        // Obtener todos los periodos
        $periodos = Periodo::orderBy('nombre')->get();
        $periodoIds = $periodos->pluck('id_periodo')->toArray();

        // Obtener detalles para esta competencia específica
        $detalles = DetalleAsignatura::where('id_competencias', $id_competencia)
            ->whereIn('codigo_matricula', $matriculas->pluck('codigo_matricula'))
            ->with(['reportesNotas' => function($query) use ($periodoIds) {
                $query->whereIn('id_periodo', $periodoIds);
            }])
            ->get()
            ->keyBy('codigo_matricula');

        // Construir respuesta
        $data = [];
        foreach ($matriculas as $matricula) {
            $detalle = $detalles->get($matricula->codigo_matricula);
            $notas = [];
            
            if ($detalle && $detalle->reportesNotas) {
                foreach ($detalle->reportesNotas as $reporte) {
                    $notas[$reporte->id_periodo] = $reporte->calificacion;
                }
            }

            $data[] = [
                'codigo_matricula' => $matricula->codigo_matricula,
                'estudiante_nombre' => $matricula->estudiante->persona->lastname . ' ' . $matricula->estudiante->persona->name,
                'id_detalle_asignatura' => $detalle ? $detalle->id_detalle_asignatura : null,
                'notas' => $notas // Ahora será un objeto asociativo: {1: "A", 2: "B", 3: "AD"}
            ];
        }
        logger()->info('Notas por competencia:', $data);
        return response()->json([
            'success' => true,
            'estudiantes' => $data,
            'periodos' => $periodos->map(function($p) {
                return [
                    'id_periodo' => $p->id_periodo,
                    'nombre' => $p->nombre,
                    'estado' => $p->estado,
                    'fecha_inicio' => $p->fecha_inicio, 
                    'fecha_fin' => $p->fecha_fin        
                ];
            })
        ]);
    }
    
    /**
     * Guardar notas masivas para una competencia
     */
    public function guardarNotasPorCompetencia(Request $request)
    {
        $id_asignatura = $request->input('id_asignatura');
        $id_competencia = $request->input('id_competencia');
        $notas = $request->input('notas', []); // notas[codigo_matricula][periodo_id] = calificacion

        if (!$id_competencia) {
            return response()->json(['success' => false, 'message' => 'No se especificó la competencia'], 400);
        }

        // Obtener periodos activos
        $periodosActivos = Periodo::where('estado', 'Proceso')->pluck('id_periodo')->toArray();

        \DB::beginTransaction();
        try {
            foreach ($notas as $codigo_matricula => $periodosNotas) {
                // Buscar o crear DetalleAsignatura
                $detalle = DetalleAsignatura::firstOrCreate(
                    [
                        'codigo_matricula' => $codigo_matricula,
                        'id_competencias' => $id_competencia
                    ],
                    ['calificacion_anual' => null]
                );

                foreach ($periodosNotas as $periodo_id => $calificacion) {
                    // Solo procesar periodos activos
                    if (!in_array($periodo_id, $periodosActivos)) {
                        continue;
                    }

                    if (empty($calificacion) || !in_array($calificacion, ['AD', 'A', 'B', 'C'])) {
                        continue;
                    }

                    ReporteNota::updateOrCreate(
                        [
                            'id_detalle_asignatura' => $detalle->id_detalle_asignatura,
                            'id_periodo' => $periodo_id
                        ],
                        [
                            'calificacion' => $calificacion,
                            'fecha_registro' => now()->toDateString()
                        ]
                    );
                }
                
                // Recalcular promedio anual
                $this->actualizarPromedioAnual($detalle->id_detalle_asignatura);
            }
            
            \DB::commit();
            return response()->json(['success' => true, 'message' => 'Notas guardadas correctamente']);
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al guardar notas: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error al guardar: ' . $e->getMessage()], 500);
        }
    }

    private function actualizarPromedioAnual($id_detalle_asignatura) {
        $periodos = Periodo::orderBy('id_periodo')->get();
        $periodoIds = $periodos->pluck('id_periodo')->toArray();
        $totalPeriodos = count($periodoIds);

        $reportes = ReporteNota::where('id_detalle_asignatura', $id_detalle_asignatura)
            ->whereIn('id_periodo', $periodoIds)
            ->get();

        if ($reportes->count() === $totalPeriodos && $totalPeriodos > 0) {
            $suma = 0; 
            $valido = true;
            foreach ($reportes as $r) {
                $valor = $this->convertirNotaANumero($r->calificacion);
                if ($valor === null) { $valido = false; break; }
                $suma += $valor;
            }

            if ($valido) {
                $promedioLetra = $this->convertirNumeroANota($suma / $totalPeriodos);
                $detalle = DetalleAsignatura::find($id_detalle_asignatura);
                $detalle->update(['calificacion_anual' => $promedioLetra]);

                // Recalcular final asignatura
                if ($detalle->asignatura) {
                    $this->recomputeCalificacionFinal($detalle->codigo_matricula, $detalle->asignatura->codigo_asignatura);
                }
            }
        }
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

    public function export($id_asignatura)
    {
        $asignatura = Asignatura::findOrFail($id_asignatura);
        
        return (new ReportesExport($id_asignatura))
            ->download('reporte_notas_' . $asignatura->nombre . '.xlsx');
    }
}