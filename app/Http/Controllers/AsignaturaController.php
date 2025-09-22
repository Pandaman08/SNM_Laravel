<?php

namespace App\Http\Controllers;
use App\Models\NivelEducativo;
use App\Models\Asignatura;
use App\Models\Grado;
use App\Models\Docente;
use Illuminate\Http\Request; 

class AsignaturaController extends Controller
{
    public function asignar($id)
    {
        $asignatura = Asignatura::where('codigo_asignatura', $id)->firstOrFail();
        // $docentes = Docente::all(); 
        $docentes = Docente::with('user.persona')->get();
        return view('pages.admin.asignaturas.asign', compact('asignatura', 'docentes'));
    }

    public function storeAsignacion(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'codigo_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
            'codigo_docente' => 'required|exists:docentes,codigo_docente'
        ]);

        $asignatura = Asignatura::where('codigo_asignatura', $request->codigo_asignatura)->firstOrFail();
        $docente = Docente::where('codigo_docente', $request->codigo_docente)->firstOrFail();
        
        // Verificar si la asignación ya existe para evitar duplicados
        if (!$asignatura->docentes()->where('asignaturas_docentes.codigo_docente', $docente->codigo_docente)->exists()) {
            // Usar attach con la fecha actual
            $asignatura->docentes()->attach($docente->codigo_docente, [
                'fecha' => now()->toDateString()
            ]);
            
            return redirect()->route('asignaturas.asignar.docentes')->with('success', 'Docente asignado con éxito');
        } else {
            return redirect()->route('asignaturas.asignar.docentes')->with('error', 'El docente ya está asignado a esta asignatura');
        }
    }

   public function updateAsignacion(Request $request)
    {
        // Validar los datos recibidos
        $request->validate([
            'codigo_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
            'codigo_docente' => 'required|exists:docentes,codigo_docente'
        ]);

        $asignatura = Asignatura::where('codigo_asignatura', $request->codigo_asignatura)->firstOrFail();
        $nuevoDocente = Docente::where('codigo_docente', $request->codigo_docente)->firstOrFail();
        
        try {
            // Verificar si ya existe una asignación con el mismo docente
            if ($asignatura->docentes()->where('asignaturas_docentes.codigo_docente', $nuevoDocente->codigo_docente)->exists()) {
                return redirect()->route('asignaturas.asignar.docentes')->with('warning', 'El docente ya está asignado a esta asignatura');
            }

            // Desconectar todos los docentes actuales de la asignatura
            $asignatura->docentes()->detach();
            
            // Conectar el nuevo docente
            $asignatura->docentes()->attach($nuevoDocente->codigo_docente, [
                'fecha' => now()->toDateString()
            ]);
            
            return redirect()->route('asignaturas.asignar.docentes')->with('success', 'Docente actualizado con éxito');
            
        } catch (\Exception $e) {
            return redirect()->route('asignaturas.asignar.docentes')->with('error', 'Error al actualizar el docente: ' . $e->getMessage());
        }
    }

      public function removeAsignacion(Request $request)
    {
        $request->validate([
            'codigo_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
            'codigo_docente' => 'required|exists:docentes,codigo_docente'
        ]);

        $asignatura = Asignatura::where('codigo_asignatura', $request->codigo_asignatura)->firstOrFail();
        $docente = Docente::where('codigo_docente', $request->codigo_docente)->firstOrFail();
        
        try {
            $asignatura->docentes()->detach($docente->codigo_docente);
            return redirect()->route('asignaturas.asignar.docentes')->with('success', 'Docente removido con éxito');
        } catch (\Exception $e) {
            return redirect()->route('asignaturas.asignar.docentes')->with('error', 'Error al remover el docente: ' . $e->getMessage());
        }
    }

    public function show(Request $request)
    {
        $nivelesEducativos = NivelEducativo::all();
        $grados = Grado::select('id_grado', 'nivel_educativo_id', 'grado')->get();
        $docentes = Docente::with('user.persona')->get(); 
        // Construir consulta base con relaciones
        $query = Asignatura::with([
            'grado.nivelEducativo',
            'docentes.user.persona'
        ]);
        
        // Aplicar filtros si existen
        if ($request->filled('nivelEducativo')) {
            $query->whereHas('grado', function($q) use ($request) {
                $q->where('nivel_educativo_id', $request->nivelEducativo);
            });
        }
        
        if ($request->filled('grado')) {
            $query->where('id_grado', $request->grado);
        }
        
        $asignaturas = $query->get();
        
        return view('pages.admin.asignaturas.asignTeacher5', compact('nivelesEducativos','grados','asignaturas', 'docentes'));
    }
    
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

        if ( $asignatura->competencias()->exists() ) {
            return redirect()->route('asignaturas.index')->with('error', 'No se puede eliminar la asignatura porque tiene relaciones activas.');
        }

        $asignatura->delete();
        return redirect()->route('asignaturas.index')->with('success', 'Asignatura eliminada correctamente.');
    }
}
