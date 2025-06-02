<?php

namespace App\Http\Controllers; 
use App\Models\Grado;
use Illuminate\Http\Request;


class GradoController extends Controller
{
    public function index()
    {
        $grados = Grado::all();
        return view('pages.admin.grados.index', compact('grados'));
    }

    public function create()
    {
        return view('pages.admin.grados.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'grado' => 'required|string|max:45' 
        ]);

        Grado::create($request->only('grado')); 
        return redirect()->route('grados.index')->with('success', 'Grado creado correctamente.');
    }

    public function edit(Grado $grado)
    {
        return view('pages.admin.grados.edit', compact('grado'));
    }

    public function update(Request $request, Grado $grado)
    {
        $request->validate([
            'grado' => 'required|string|max:45' 
        ]);

        $grado->update($request->only('grado'));
        return redirect()->route('grados.index')->with('success', 'Grado actualizado correctamente.');
    }

    public function destroy(Grado $grado)
    {
        $grado->delete();
        return redirect()->route('grados.index')->with('success', 'Grado eliminado correctamente.');
    }
}
