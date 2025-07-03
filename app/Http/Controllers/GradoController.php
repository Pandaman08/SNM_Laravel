<?php

namespace App\Http\Controllers; 
use App\Models\Grado;
use App\Models\NivelEducativo;
use Illuminate\Http\Request;


class GradoController extends Controller
{
    public function index() 
    {
        $grados = Grado::with('nivelEducativo')->get();
        return view('pages.admin.grados.index', compact('grados'));
    }

    public function create()
    {
        $nivelesEducativos = NivelEducativo::activos()->get();
        return view('pages.admin.grados.create', compact('nivelesEducativos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grado'                => 'required|integer|between:1,6',
            'nivel_educativo_id'   => 'required|exists:niveles_educativos,id_nivel_educativo',
        ]);

        Grado::create($data);

        return redirect()->route('grados.index')
                         ->with('success', 'Grado creado correctamente.');
    }

    public function edit(Grado $grado)
    {
        $nivelesEducativos = NivelEducativo::activos()->get();
        return view('pages.admin.grados.edit', compact('grado','nivelesEducativos'));
    }

    public function update(Request $request, Grado $grado)
    {
        $data = $request->validate([
            'grado'                => 'required|integer|between:1,6',
            'nivel_educativo_id'   => 'required|exists:niveles_educativos,id_nivel_educativo',
        ]);

        return redirect()->route('grados.index')
                         ->with('success', 'Grado actualizado correctamente.');
    }


    public function destroy(Grado $grado)
    {
        if ($grado->secciones()->exists()) {
            return redirect()->route('grados.index')
                             ->with('error', 'No se puede eliminar el grado porque tiene secciones asociadas.');
        }

        $grado->delete();

        return redirect()->route('grados.index')
                         ->with('success', 'Grado eliminado correctamente.');
    }
}