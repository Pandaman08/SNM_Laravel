<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Competencia;
use Illuminate\Http\Request;
use App\Models\ReporteNota;
use App\Models\DetalleAsignatura;
use App\Models\Periodo;
use App\Models\Matricula;
use App\Models\TipoCalificacion;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;


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
                $query->where('id_periodo', $periodoId)
                    ->with('tipoCalificacion');
            }
        ])
            ->where('codigo_matricula', $matricula->codigo_matricula)
            ->get();
    }


    public function create($codigo_matricula, $id_asignatura)
    {
        $matricula = Matricula::findOrFail($codigo_matricula);
        $tipos_cal = TipoCalificacion::all();
        $periodos = Periodo::all();

        $competencias = Competencia::with('detallesAsignatura')
            ->where('codigo_asignatura', $id_asignatura)
            ->get();

        // Flatten the collection of detalles_asignatura
        $detalles_asignatura = $competencias->flatMap(function ($competencia) use ($codigo_matricula)  {
             return DetalleAsignatura::where('id_competencias', $competencia->id_competencias)
                         ->where('codigo_matricula', $codigo_matricula)   
                           ->get();
        });

        \Log::info('detalle',$detalles_asignatura->toArray());

        return view('pages.admin.reporte_notas.create', compact('matricula', 'detalles_asignatura', 'tipos_cal', 'periodos', 'id_asignatura'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_detalle_asignatura' => 'required|exists:detalles_asignatura,id_detalle_asignatura',
            'id_tipo_calificacion' => 'required|exists:tipos_calificacion,id_tipo_calificacion',
            'id_periodo' => 'required|exists:periodos,id_periodo',
            'observacion' => 'max:255',
            'fecha_registro' => 'required|date',
            'id_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
        ]);

        // Crear el reporte con los campos definidos explícitamente
        ReporteNota::create([
            'id_detalle_asignatura' => $validated['id_detalle_asignatura'],
            'id_tipo_calificacion' => $validated['id_tipo_calificacion'],
            'id_periodo' => $validated['id_periodo'],
            'observacion' => $validated['observacion'],
            'fecha_registro' => $validated['fecha_registro'],
        ]);

        return redirect()
            ->route('docentes.estudiantes', ['id_asignatura' => $validated['id_asignatura']])
            ->with('success', 'Nota registrada correctamente.');
    }

    public function getDetalles($id_asignatura)
    {
        // $asignatura = Asignatura::findOrFail($id_asignatura);

        $competencias = Competencia::where('codigo_asignatura', $id_asignatura)->get();
        $competenciaIds = $competencias->pluck('id_competencias');

        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)->get();
        $detalleIds = $detalles->pluck('id_detalle_asignatura');


        return ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->with([
                'detalleAsignatura' => function ($query) {
                    $query->select('*');
                },
                'tipoCalificacion',
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
          \Log::info('compes',  $competenciaIds->toArray());

        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)
            ->where('codigo_matricula', $codigo_matricula)
            ->get();
        \Log::info('DEATLLE', $detalles->toArray());

       
        $detalleIds = $detalles->pluck('id_detalle_asignatura');

       
        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->whereIn('id_periodo', [3, 4, 5])
            ->with([
                'detalleAsignatura.competencia',
                'tipoCalificacion',
                'periodo'
            ])
            ->get();
        \Log::info('reportes', $reportes->toArray());


        $competencias->each(function ($competencia) use ($detalles, $reportes) {
            $competencia->detallesAsignatura = $detalles->where('id_competencias', $competencia->id_competencias);

            $competencia->detallesAsignatura->each(function ($detalle) use ($reportes) {
                $detalle->reportesNotas = $reportes->where('id_detalle_asignatura', $detalle->id_detalle_asignatura);
            });
        });

        return view('pages.admin.reporte_notas.estudiantes', compact(
            'matricula',
            'competencias',
            'asignatura'
        ));
    }

    public function docente_view($id_asignatura)
    {
        $asignatura = Asignatura::findOrFail($id_asignatura);

        /*  $competencias = Competencia::where('codigo_asignatura', $id_asignatura)->get();
          $competenciaIds = $competencias->pluck('id_competencias');

          $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)->get();
          $detalleIds = $detalles->pluck('id_detalle_asignatura'); */


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
            ->leftJoin('tipos_calificacion as tc', 'r.id_tipo_calificacion', '=', 'tc.id_tipo_calificacion')
            ->select(
                'p.persona_id',
                \DB::raw("CONCAT(p.name, ' ', p.lastname) as estudiante"),
                \DB::raw("MAX(CASE WHEN per.id_periodo = 1 THEN tc.codigo ELSE NULL END) as periodo1"),
                \DB::raw("MAX(CASE WHEN per.id_periodo = 2 THEN tc.codigo ELSE NULL END) as periodo2"),
                \DB::raw("MAX(CASE WHEN per.id_periodo = 3 THEN tc.codigo ELSE NULL END) as periodo3"),
            )
            ->groupBy('p.persona_id', 'p.name', 'p.lastname')
            ->orderBy('p.lastname')
            ->get();


        /*$reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->with([
                'detalleAsignatura' => function ($query) {
                    $query->select('*');
                },
                'tipoCalificacion',
                'periodo'
            ])
            ->get(); */

        return view('pages.admin.reporte_notas.docentes-view', compact('reportes', 'asignatura'));
    }

    public function exportExcel($id_asignatura)
    {
        $reportes = $this->getDetalles($id_asignatura);

        $data = $reportes->map(function ($r) {
            return [
                'Código Matrícula' => $r->detalleAsignatura->matricula->codigo_matricula ?? '',
                'Estudiante' => $r->detalleAsignatura->matricula->estudiante->nombre_completo ?? '',
                'Asignatura' => $r->detalleAsignatura->asignatura->nombre_asignatura ?? '',
                'Tipo Calificación' => $r->tipoCalificacion->nombre ?? '',
                'Periodo' => $r->periodo->nombre ?? '',
                'Observación' => $r->observacion,
                'Fecha Registro' => $r->fecha_registro,
            ];
        });

        $export = new class ($data) implements FromCollection, WithHeadings {
            private $data;

            public function __construct($data)
            {
                $this->data = collect($data);
            }

            public function collection()
            {
                return $this->data;
            }

            public function headings(): array
            {
                return $this->data->isNotEmpty()
                    ? array_keys($this->data->first())
                    : [];
            }
        };

        return Excel::download($export, 'reporte_notas_' . Str::slug($id_asignatura) . '.xlsx');
    }

}
