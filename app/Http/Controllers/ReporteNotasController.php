<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
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

        $periodos = Periodo::whereIn('id_periodo', [1,2,3, 4 ])
            ->orderBy('id_periodo')
            ->get();

        $hoy = now();
        $periodoActual = null;

        foreach ($periodos as $periodo) {
            if ($hoy->between($periodo->fecha_inicio, $periodo->fecha_fin)) {
                $periodoActual = $periodo;
                break;
            }
        }

        // Obtener todas las notas reportadas para estos detalles
        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', [1,2,3, 4])
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        // Organizar los datos para la vista
        $competencias->each(function ($competencia) use ($detalles, $reportes) {
            $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias);

            $competencia->detallesAsignatura->each(function ($detalle) use ($reportes) {
                // Asignar reportes a cada detalle
                $detalle->reportesNotas = $reportes->get($detalle->id_detalle_asignatura, collect());

                // Calcular promedio si hay 4 notas (uno por cada bimestre)
                if ($detalle->reportesNotas && $detalle->reportesNotas->count() === 4) {
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

                    $detalle->promedio = $valido ? $this->convertirNumeroANota($suma / 4) : null;
                } else {
                    $detalle->promedio = null;
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

        // Obtener reportes de notas para los bimestres
        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', [1,2,3, 4])
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        $asignaturas->each(function ($asignatura) use ($competencias, $detalles, $reportes) {
            $asignatura->competencias = $competencias->where('codigo_asignatura', $asignatura->codigo_asignatura);

            $asignatura->competencias->each(function ($competencia) use ($detalles, $reportes) {
                $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias);

                $competencia->detallesAsignatura->each(function ($detalle) use ($reportes) {
                    $detalle->reportesNotas = $reportes->get($detalle->id_detalle_asignatura, collect());

                    if ($detalle->reportesNotas->count() === 4) {
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

                        $detalle->promedio = $valido ? $this->convertirNumeroANota($suma / 4) : null;
                    } else {
                        $detalle->promedio = null;
                    }
                });
            });
        });

        return view('pages.admin.reporte_notas.tutor-estudiantes', [
            'matricula' => $matricula,
            'asignaturas' => $asignaturas
        ]);
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

        // Obtener reportes de notas para los bimestres
        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', [1,2,3, 4])
            ->with(['periodo'])
            ->get()
            ->groupBy('id_detalle_asignatura');

        $asignaturas->each(function ($asignatura) use ($competencias, $detalles, $reportes) {
            $asignatura->competencias = $competencias->where('codigo_asignatura', $asignatura->codigo_asignatura);

            $asignatura->competencias->each(function ($competencia) use ($detalles, $reportes) {
                $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias);

                $competencia->detallesAsignatura->each(function ($detalle) use ($reportes) {
                    $detalle->reportesNotas = $reportes->get($detalle->id_detalle_asignatura, collect());

                    if ($detalle->reportesNotas->count() === 4) {
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

                        $detalle->promedio = $valido ? $this->convertirNumeroANota($suma / 4) : null;
                    } else {
                        $detalle->promedio = null;
                    }
                });
            });
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
}