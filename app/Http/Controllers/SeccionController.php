<?php

namespace App\Http\Controllers;
use App\Models\Seccion;
use App\Models\Grado;
use Illuminate\Http\Request;

class SeccionController extends Controller
{
    public function index()
    {
        $secciones = Seccion::with(['grado', 'grado.nivelEducativo', 'docentes'])
            ->orderBy('id_grado')
            ->orderBy('seccion')
            ->get();
        return view('pages.admin.secciones.index', compact('secciones'));
    }

    public function create()
    {
        $grados = Grado::with('nivelEducativo')->orderBy('nivel_educativo_id')->orderBy('grado')->get();
        $seccionesExistentes = Seccion::select('id_grado', 'seccion')
            ->get()
            ->groupBy('id_grado')
            ->map(fn($s) => $s->pluck('seccion')->toArray())
            ->toArray();
        
        return view('pages.admin.secciones.create', compact('grados', 'seccionesExistentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'seccion' => 'required|string|max:1',
            'vacantes_seccion' => 'required|integer|min:1|max:50',
            'estado_vacantes' => 'boolean'
        ]);

        // Verificar duplicado
        if (Seccion::where('id_grado', $request->id_grado)
                ->where('seccion', strtoupper($request->seccion))
                ->exists()) {
            return back()
                ->withInput()
                ->withErrors(['seccion' => 'Esta sección ya existe para el grado seleccionado.']);
        }

        $seccion = new Seccion($request->all());
        $seccion->seccion = strtoupper($request->seccion);
        $seccion->estado_vacantes = $request->has('estado_vacantes');
        $seccion->save();

        return redirect()->route('secciones.index')
            ->with('success', 'Sección registrada correctamente.');
    }

    public function edit($id)
    {
        $seccion = Seccion::with('grado')->findOrFail($id);
        $grados = Grado::with('nivelEducativo')->orderBy('nivel_educativo_id')->orderBy('grado')->get();
        $seccionesExistentes = Seccion::where('id_seccion', '!=', $id)
            ->get()
            ->groupBy('id_grado')
            ->map(fn($s) => $s->pluck('seccion')->toArray())
            ->toArray();
        
        return view('pages.admin.secciones.edit', compact('seccion', 'grados', 'seccionesExistentes'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'seccion' => 'required|string|max:1',
            'vacantes_seccion' => 'required|integer|min:1|max:50',
            'estado_vacantes' => 'boolean'
        ]);

        $seccion = Seccion::findOrFail($id);
        
        // Verificar duplicado
        if (Seccion::where('id_grado', $request->id_grado)
                ->where('seccion', strtoupper($request->seccion))
                ->where('id_seccion', '!=', $id)
                ->exists()) {
            return back()
                ->withInput()
                ->withErrors(['seccion' => 'Esta sección ya existe para el grado seleccionado.']);
        }
        
        if ($seccion->cantidadEstudiantes() > $request->vacantes_seccion) {
            return back()->withErrors([
                'vacantes_seccion' => 'No se puede reducir el número de vacantes por debajo del número de estudiantes matriculados.'
            ]);
        }

        $seccion->fill($request->all());
        $seccion->seccion = strtoupper($request->seccion);
        $seccion->estado_vacantes = $request->has('estado_vacantes');
        $seccion->save();

        return redirect()->route('secciones.index')
            ->with('success', 'Sección actualizada correctamente.');
    }

    public function destroy($id)
    {
        $seccion = Seccion::findOrFail($id);

        if ($seccion->cantidadEstudiantes() > 0) {
            return back()->withErrors([
                'error' => 'No se puede eliminar la sección porque tiene estudiantes matriculados.'
            ]);
        }

        $seccion->delete();

        return redirect()->route('secciones.index')
            ->with('success', 'Sección eliminada correctamente.');
    }

    public function actualizarEstadoVacantes($id)
    {
        $seccion = Seccion::findOrFail($id);
        $seccion->estado_vacantes = !$seccion->estado_vacantes;
        $seccion->save();

        return redirect()->route('secciones.index')
            ->with('success', 'Estado de vacantes actualizado correctamente.');
    }

    public function seccionesPorGrado($gradoId)
    {
        $secciones = Seccion::where('id_grado', $gradoId)
            ->where('estado_vacantes', true)
            ->get();
        
        return response()->json($secciones);
    }
}