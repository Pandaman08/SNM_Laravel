<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoCalificacion;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Exception;
class TipoCalificacionController extends Controller
{
        public function index(Request $request)
    {
        $searchTerm = $request->input('buscarpor');
        
        $tipos = TipoCalificacion::query()
            ->when($searchTerm, function($query) use ($searchTerm) {
                $query->where('nombre', 'like', '%'.$searchTerm.'%')
                      ->orWhere('codigo', 'like', '%'.$searchTerm.'%')
                      ->orWhere('descripcion', 'like', '%'.$searchTerm.'%');
            })
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.tipos_calificacion.index', [
            'tipos' => $tipos,
            'buscarpor' => $searchTerm
        ]);
    }

    public function create()
    {
        return view('pages.admin.tipos_calificacion.create');
    }

    public function store(Request $request)
    {
        try {
            $messages = [
                'codigo.required' => 'El código es obligatorio.',
                'codigo.max' => 'El código no debe exceder 45 caracteres.',
                'codigo.unique' => 'Este código ya está registrado.',
                
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no debe exceder 80 caracteres.',
                
                'descripcion.max' => 'La descripción no debe exceder 200 caracteres.',
            ];

            $validatedData = $request->validate([
                'codigo' => 'required|string|max:45|unique:tipos_calificacion',
                'nombre' => 'required|string|max:80',
                'descripcion' => 'nullable|string|max:200',
            ], $messages);

            $tipo = TipoCalificacion::create($validatedData);
            
            Log::info("Nuevo tipo de calificación creado", ['tipo_calificacion' => $tipo]);

            return redirect()->route('tipos-calificacion.index')
                ->with('success', 'Tipo de calificación creado con éxito');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            Log::error("Error al crear tipo de calificación", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el tipo de calificación: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $tipo = TipoCalificacion::findOrFail($id);
        return view('pages.admin.tipos_calificacion.edit', [
            'tipo' => $tipo
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $tipo = TipoCalificacion::findOrFail($id);

            $messages = [
                'codigo.required' => 'El código es obligatorio.',
                'codigo.max' => 'El código no debe exceder 45 caracteres.',
                'codigo.unique' => 'Este código ya está registrado.',
                
                'nombre.required' => 'El nombre es obligatorio.',
                'nombre.max' => 'El nombre no debe exceder 80 caracteres.',
                
                'descripcion.max' => 'La descripción no debe exceder 200 caracteres.',
            ];

            $validatedData = $request->validate([
                'codigo' => 'required|string|max:45|unique:tipos_calificacion,codigo,'.$tipo->id_tipo_calificacion.',id_tipo_calificacion',
                'nombre' => 'required|string|max:80',
                'descripcion' => 'nullable|string|max:200',
            ], $messages);

            $tipo->update($validatedData);
            
            Log::info("Tipo de calificación actualizado", ['tipo_calificacion' => $tipo]);

            return redirect()->route('tipos-calificacion.index')
                ->with('success', 'Tipo de calificación actualizado con éxito');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            Log::error("Error al actualizar tipo de calificación", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el tipo de calificación: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $tipo = TipoCalificacion::findOrFail($id);

            // Verificar si el tipo está en uso antes de eliminar
            if ($tipo->reportesNotas()->exists()) {
                throw new Exception('No se puede eliminar porque tiene calificaciones asociadas.');
            }

            $tipo->delete();
            
            Log::info("Tipo de calificación eliminado", ['id_tipo_calificacion' => $id]);

            return redirect()->route('tipos-calificacion.index')
                ->with('success', 'Tipo de calificación eliminado con éxito');

        } catch (Exception $e) {
            Log::error("Error al eliminar tipo de calificación", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al eliminar el tipo de calificación: ' . $e->getMessage());
        }
    }
}
