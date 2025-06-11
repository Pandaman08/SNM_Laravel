<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
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

        $detalles_asignatura = DetalleAsignatura::where('codigo_matricula', $codigo_matricula)->get();

        // Creamos una colección de una sola matrícula para usar foreach



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

    public function docente_view($id_asignatura)
    {
        $asignatura = Asignatura::findOrFail($id_asignatura);
        $reportes = collect();

        foreach ($asignatura->competencias as $competencia) {
            foreach ($competencia->detallesAsignatura as $detalle) {
                $reportesDetalle = ReporteNota::where('id_detalle_asignatura', $detalle->id_detalle_asignatura)
                    ->with(['detalleAsignatura.asignatura', 'tipoCalificacion', 'periodo'])
                    ->get();

                $reportes = $reportes->merge($reportesDetalle);
            }
        }

        return view('pages.admin.reporte_notas.docentes-view', compact('reportes', 'asignatura'));
    }

    public function exportExcel($id_asignatura)
    {
        $reportes = ReporteNota::whereHas('detalleAsignatura', function ($q) use ($id_asignatura) {
            $q->where('codigo_asignatura', $id_asignatura);
        })->with([
                    'detalleAsignatura.asignatura',
                    'detalleAsignatura.matricula.estudiante',
                    'tipoCalificacion',
                    'periodo',
                ])->get();

        $data = $reportes->map(function ($r) {
            return [
                'Código Matrícula' => $r->detalleAsignatura->matricula->codigo_matricula ?? '',
                'Estudiante' => $r->detalleAsignatura->matricula->estudiante->nombre_completo ?? '',
                'Asignatura' => $r->detalleAsignatura->asignatura->nombre_asignatura ?? '',
                'Tipo Calificación' => $r->tipoCalificacion->nombre ?? '',
                'Periodo' => $r->periodo->nombre ?? '',
                'Observación' => $r->observacion,
                'Fecha Registro' => $r->fecha_registro->format('Y-m-d'),
            ];
        });

        $nombreArchivo = 'reporte_notas_' . Str::slug($id_asignatura) . '.xlsx';

        return Excel::download(new class ($data) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings {
            private $data;
            public function __construct($data)
            {
                $this->data = $data;
            }

            public function collection()
            {
                return $this->data;
            }

            public function headings(): array
            {
                return array_keys($this->data->first() ?? []);
            }
        }, $nombreArchivo);
    }

}
