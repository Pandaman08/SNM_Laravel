<?php

namespace App\Http\Controllers;

use App\Models\Asignatura;
use App\Models\Grado;
use Illuminate\Http\Request;

class AsignaturaController extends Controller
{
    public function index()
    {
        $asignaturas = Asignatura::with('grado')->get();
        return view('pages.admin.asignaturas.index', compact('asignaturas'));
    }

    public function create()
    {
        $grados = Grado::all();
        return view('pages.admin.asignaturas.create', compact('grados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'nombre' => 'required|string|max:100',
        ]);

        Asignatura::create($request->all());

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura registrada correctamente.');
    }

    public function edit($id)
    {
        $asignatura = Asignatura::findOrFail($id);
        $grados = Grado::all();
        return view('pages.admin.asignaturas.edit', compact('asignatura', 'grados'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'nombre' => 'required|string|max:100',
        ]);

        $asignatura = Asignatura::findOrFail($id);
        $asignatura->update($request->all());

        return redirect()->route('asignaturas.index')->with('success', 'Asignatura actualizada correctamente.');
    }

    public function destroy($id)
    {
        $asignatura = Asignatura::findOrFail($id);

        if ($asignatura->detallesAsignatura()->exists() || $asignatura->competencias()->exists() || $asignatura->docentes()->exists()) {
            return redirect()->route('asignaturas.index')->with('error', 'No se puede eliminar la asignatura porque tiene relaciones activas.');
        }

        $asignatura->delete();
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura eliminada correctamente.');
    }
}
