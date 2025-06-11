<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competencia;
use Illuminate\Support\Facades\Auth;
use App\Models\Asignatura;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Exception;
class CompetenciaController extends Controller
{
      public function index(Request $request)
    {
        $searchTerm = $request->input('buscarpor');
        
        $competencias = Competencia::with('asignatura')
            ->when($searchTerm, function($query) use ($searchTerm) {
                $query->where('descripcion', 'like', '%'.$searchTerm.'%')
                      ->orWhereHas('asignatura', function($q) use ($searchTerm) {
                          $q->where('nombre', 'like', '%'.$searchTerm.'%');
                      });
            })
            ->orderBy('id_competencias', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.competencias.index', [
            'competencias' => $competencias,
            'buscarpor' => $searchTerm
        ]);
    }

    public function create()
    {
        $asignaturas = Asignatura::orderBy('nombre')->get();
        return view('pages.admin.competencias.create', compact('asignaturas'));
    }

    public function store(Request $request)
    {
        try {
            $messages = [
                'codigo_asignatura.required' => 'La asignatura es obligatoria.',
                'codigo_asignatura.exists' => 'La asignatura seleccionada no es válida.',
                
                'descripcion.required' => 'La descripción es obligatoria.',
                'descripcion.string' => 'La descripción debe ser texto.',
                'descripcion.max' => 'La descripción no debe exceder 150 caracteres.',
            ];

            $validatedData = $request->validate([
                'codigo_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
                'descripcion' => 'required|string|max:150',
            ], $messages);

            $competencia = Competencia::create($validatedData);
            
            Log::info("Nueva competencia creada", ['competencia' => $competencia]);

            return redirect()->route('competencias.index')
                ->with('success', 'Competencia creada con éxito');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            Log::error("Error al crear competencia", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear la competencia: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $competencia = Competencia::findOrFail($id);
        $asignaturas = Asignatura::orderBy('nombre')->get();
        
        return view('pages.admin.competencias.edit', [
            'competencia' => $competencia,
            'asignaturas' => $asignaturas
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $competencia = Competencia::findOrFail($id);

            $messages = [
                'codigo_asignatura.required' => 'La asignatura es obligatoria.',
                'codigo_asignatura.exists' => 'La asignatura seleccionada no es válida.',
                
                'descripcion.required' => 'La descripción es obligatoria.',
                'descripcion.string' => 'La descripción debe ser texto.',
                'descripcion.max' => 'La descripción no debe exceder 150 caracteres.',
            ];

            $validatedData = $request->validate([
                'codigo_asignatura' => 'required|exists:asignaturas,codigo_asignatura',
                'descripcion' => 'required|string|max:150',
            ], $messages);

            $competencia->update($validatedData);
            
            Log::info("Competencia actualizada", ['competencia' => $competencia]);

            return redirect()->route('competencias.index')
                ->with('success', 'Competencia actualizada con éxito');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            Log::error("Error al actualizar competencia", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar la competencia: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $competencia = Competencia::findOrFail($id);
            $competencia->delete();
            
            Log::info("Competencia eliminada", ['id_competencias' => $id]);

            return redirect()->route('competencias.index')
                ->with('success', 'Competencia eliminada con éxito');

        } catch (Exception $e) {
            Log::error("Error al eliminar competencia", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al eliminar la competencia: ' . $e->getMessage());
        }
    }
}
