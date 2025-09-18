<?php
namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class ReporteController extends Controller
{
    public function matriculados()
    {

        $matriculasPorSeccion = Matricula::with('seccion')
            ->selectRaw('seccion_id, COUNT(*) as total')
            ->groupBy('seccion_id')
            ->get();


        $matriculasPorEstado = Matricula::selectRaw('estado, COUNT(*) as total')
            ->groupBy('estado')
            ->get();

        $alumnos = Matricula::with(['estudiante.persona', 'seccion'])
            ->orderBy('seccion_id')
            ->get();

        return view('pages.admin.matriculas.reporte', compact(
            'matriculasPorSeccion',
            'matriculasPorEstado',
            'alumnos'
        ));
    }
}
