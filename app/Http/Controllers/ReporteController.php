<?php
namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
     public function matriculados(Request $request)
    {
        // Filtro por nivel (Inicial, Primaria, Secundaria)
        $nivel = $request->get('nivel'); 

        // Query base con relaciones
        $query = Matricula::with(['estudiante.persona', 'seccion.grado.nivelEducativo']);

        if ($nivel) {
            $query->whereHas('seccion.grado.nivelEducativo', function ($q) use ($nivel) {
                $q->where('nombre', $nivel);
            });
        }

        // Alumnos filtrados
        $alumnos = $query->orderBy('seccion_id')->get();

        // Totales por sección filtrados 
        $matriculasPorSeccion = Matricula::with(['seccion.grado.nivelEducativo'])
            ->when($nivel, function ($q) use ($nivel) {
                $q->whereHas('seccion.grado.nivelEducativo', function ($sub) use ($nivel) {
                    $sub->where('nombre', $nivel);
                });
            })
            ->selectRaw('seccion_id, COUNT(*) as total')
            ->groupBy('seccion_id')
            ->get();

        // Totales por estado filtrados
        $matriculasPorEstado = Matricula::when($nivel, function ($q) use ($nivel) {
                $q->whereHas('seccion.grado.nivelEducativo', function ($sub) use ($nivel) {
                    $sub->where('nombre', $nivel);
                });
            })
            ->selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();

        return view('pages.admin.matriculas.reporte', compact(
            'matriculasPorSeccion',
            'matriculasPorEstado',
            'alumnos',
            'nivel'
        ));
    }
}