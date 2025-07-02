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
        $detalles_asignatura = $competencias->flatMap(function ($competencia) {
            return DetalleAsignatura::where('id_competencias', $competencia->id_competencias)->get();
        });


        return view('pages.admin.reporte_notas.create', compact('matricula', 'detalles_asignatura', 'tipos_cal', 'periodos', 'id_asignatura'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_detalle_asignatura' => 'required|exists:detalles_asignatura,id_detalle_asignatura',
            'id_tipo_calificacion' => 'required|exists:tipos_calificacion,id_tipo_calificacion',
            'id_periodo' => 'required|exists:periodos,id_periodo',
            'observacion' => 'required|string|max:255',
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

    public function docente_view($id_asignatura)
    {
        $asignatura = Asignatura::findOrFail($id_asignatura);

        $competencias = Competencia::where('codigo_asignatura', $id_asignatura)->get();
        $competenciaIds = $competencias->pluck('id_competencias');

        $detalles = DetalleAsignatura::whereIn('id_competencias', $competenciaIds)->get();
        $detalleIds = $detalles->pluck('id_detalle_asignatura');


          $datos = \DB::table('detalles_asignatura as da')
          ->join('competencias as c', 'c.id_competencias', '=', 'c.id_competencias')
          ->join('asignaturas as a', 'c.codigo_asignatura', '=', 'a.codigo_asignatura')->where('a.codigo_asignatura', '=', $id_asignatura)->select(
            'p.idproducto',
            'p.descripcion',
            'u.descripcion as unidad',
            'p.precio',
            'p.stock'
        )->get();

        $reportes = ReporteNota::whereIn('id_detalle_asignatura', $detalleIds)
            ->with([
                'detalleAsignatura' => function ($query) {
                    $query->select('*');
                },
                'tipoCalificacion',
                'periodo'
            ])
            ->get();

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
