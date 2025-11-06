<?php

namespace App\Http\Controllers; 
use App\Models\Grado;
use App\Models\NivelEducativo;
use Illuminate\Http\Request;

class GradoController extends Controller
{
    public function index() 
    {
        $grados = Grado::with('nivelEducativo')->orderBy('nivel_educativo_id')->orderBy('grado')->get();
        return view('pages.admin.grados.index', compact('grados'));
    }

    public function create()
    {
        $nivelesEducativos = NivelEducativo::activos()->get();
        $gradosExistentes = Grado::pluck('grado', 'nivel_educativo_id')
            ->groupBy(fn($_, $key) => $key)
            ->map(fn($item) => $item->values()->toArray())
            ->toArray();
        
        return view('pages.admin.grados.create', compact('nivelesEducativos', 'gradosExistentes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'grado'                => 'required|integer|between:1,6|unique:grados,grado,NULL,id_grado,nivel_educativo_id,' . $request->nivel_educativo_id,
            'nivel_educativo_id'   => 'required|exists:niveles_educativos,id_nivel_educativo',
        ], [
            'grado.unique' => 'Este grado ya existe para este nivel educativo.'
        ]);

        Grado::create($data);
        return redirect()->route('grados.index')->with('success', 'Grado creado correctamente.');
    }

    public function edit(Grado $grado)
    {
        $nivelesEducativos = NivelEducativo::activos()->get();
        $gradosExistentes = Grado::where('id_grado', '!=', $grado->id_grado)
            ->get()
            ->groupBy('nivel_educativo_id')
            ->map(fn($g) => $g->pluck('grado')->toArray())
            ->toArray();
        
        return view('pages.admin.grados.edit', compact('grado', 'nivelesEducativos', 'gradosExistentes'));
    }

    public function update(Request $request, Grado $grado)
    {
        $data = $request->validate([
            'grado'                => 'required|integer|between:1,6|unique:grados,grado,' . $grado->id_grado . ',id_grado,nivel_educativo_id,' . $request->nivel_educativo_id,
            'nivel_educativo_id'   => 'required|exists:niveles_educativos,id_nivel_educativo',
        ], [
            'grado.unique' => 'Este grado ya existe para este nivel educativo.'
        ]);

        $grado->update($data);
        return redirect()->route('grados.index')->with('success', 'Grado actualizado correctamente.');
    }

    public function destroy(Grado $grado)
    {
        if ($grado->secciones()->exists()) {
            return redirect()->route('grados.index')
                             ->with('error', 'No se puede eliminar el grado porque tiene secciones asociadas.');
        }

        $grado->delete();
        return redirect()->route('grados.index')->with('success', 'Grado eliminado correctamente.');
    }
}