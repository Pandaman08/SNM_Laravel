<?php

namespace App\Http\Controllers;
use App\Models\Seccion;
use App\Models\Grado;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    // Mostrar lista de secciones
    public function index()
    {
        $secciones = Seccion::with('grado')->get();
        return view('pages.admin.secciones.index', compact('secciones'));
    }

    // Mostrar formulario para crear nueva sección
    public function create()
    {
        $grados = Grado::all();
        return view('pages.admin.secciones.create', compact('grados'));
    }

    // Guardar nueva sección
    public function store(Request $request)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'seccion' => 'required|string|max:1',
        ]);

        Seccion::create($request->all());

        return redirect()->route('secciones.index')->with('success', 'Sección registrada correctamente.');
    }

    // Mostrar formulario para editar una sección existente
    public function edit($id)
    {
        $seccion = Seccion::findOrFail($id);
        $grados = Grado::all();
        return view('pages.admin.secciones.edit', compact('seccion', 'grados'));
    }

    // Actualizar sección
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'seccion' => 'required|string|max:1',
        ]);

        $seccion = Seccion::findOrFail($id);
        $seccion->update($request->all());

        return redirect()->route('secciones.index')->with('success', 'Sección actualizada correctamente.');
    }

    // Eliminar sección
    public function destroy($id)
    {
        $seccion = Seccion::findOrFail($id);
        $seccion->delete();

        return redirect()->route('secciones.index')->with('success', 'Sección eliminada correctamente.');
    }
}
