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
        $secciones = Seccion::with(['grado', 'grado.nivelEducativo', 'docentes'])->get();
        return view('pages.admin.secciones.index', compact('secciones'));
    }

    // Mostrar formulario para crear nueva sección
    public function create()
    {
        $grados = Grado::with('nivelEducativo')->get();
        return view('pages.admin.secciones.create', compact('grados'));
    }

    // Guardar nueva sección
    public function store(Request $request)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'seccion' => 'required|string|max:1',
            'vacantes_seccion' => 'required|integer|min:1|max:50',
            'estado_vacantes' => 'boolean'
        ]);

        $seccion = new Seccion($request->all());
        $seccion->estado_vacantes = $request->has('estado_vacantes');
        $seccion->save();

        return redirect()->route('secciones.index')
            ->with('success', 'Sección registrada correctamente.');
    }

    // Mostrar detalles de una sección
    public function show($id)
    {
        $seccion = Seccion::with(['grado', 'grado.nivelEducativo', 'docentes', 'matriculas'])
            ->findOrFail($id);
        
        return view('pages.admin.secciones.show', compact('seccion'));
    }

    // Mostrar formulario para editar una sección existente
    public function edit($id)
    {
        $seccion = Seccion::with('grado')->findOrFail($id);
        $grados = Grado::with('nivelEducativo')->get();
        return view('pages.admin.secciones.edit', compact('seccion', 'grados'));
    }

    // Actualizar sección
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_grado' => 'required|exists:grados,id_grado',
            'seccion' => 'required|string|max:1',
            'vacantes_seccion' => 'required|integer|min:1|max:50',
            'estado_vacantes' => 'boolean'
        ]);

        $seccion = Seccion::findOrFail($id);
        
        // Verificar si hay matrículas antes de actualizar
        if ($seccion->cantidadEstudiantes() > $request->vacantes_seccion) {
            return back()->withErrors([
                'vacantes_seccion' => 'No se puede reducir el número de vacantes por debajo del número de estudiantes matriculados.'
            ]);
        }

        $seccion->fill($request->all());
        $seccion->estado_vacantes = $request->has('estado_vacantes');
        $seccion->save();

        return redirect()->route('secciones.index')
            ->with('success', 'Sección actualizada correctamente.');
    }

    // Eliminar sección
    public function destroy($id)
    {
        $seccion = Seccion::findOrFail($id);

        // Verificar si hay matrículas activas
        if ($seccion->cantidadEstudiantes() > 0) {
            return back()->withErrors([
                'error' => 'No se puede eliminar la sección porque tiene estudiantes matriculados.'
            ]);
        }

        $seccion->delete();

        return redirect()->route('secciones.index')
            ->with('success', 'Sección eliminada correctamente.');
    }

    // Actualizar estado de vacantes
    public function actualizarEstadoVacantes($id)
    {
        $seccion = Seccion::findOrFail($id);
        $seccion->estado_vacantes = !$seccion->estado_vacantes;
        $seccion->save();

        return redirect()->route('secciones.index')
            ->with('success', 'Estado de vacantes actualizado correctamente.');
    }

    // Obtener secciones por grado
    public function seccionesPorGrado($gradoId)
    {
        $secciones = Seccion::where('id_grado', $gradoId)
            ->where('estado_vacantes', true)
            ->get();
        
        return response()->json($secciones);
    }
}
