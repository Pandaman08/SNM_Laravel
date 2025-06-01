<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Models\AnioEscolar;
class AnioEscolarController extends Controller
{
  public function index(Request $request)
{
    $searchTerm = $request->input('buscarpor');
    
    $anios = AnioEscolar::query()
        ->when($searchTerm, function($query) use ($searchTerm) {
            $query->where('anio', 'like', '%'.$searchTerm.'%')
                  ->orWhere('descripcion', 'like', '%'.$searchTerm.'%');
        })
        ->orderBy('fecha_inicio', 'desc')
        ->paginate(10)
        ->withQueryString();

    return view('pages.admin.anios_escolares.index', [
        'anios' => $anios,
        'buscarpor' => $searchTerm
    ]);
}
public function create()
{
    return view('pages.admin.anios_escolares.create');
}

public function store(Request $request)
{
    try {
        $messages = [
            'anio.required' => 'El año escolar es obligatorio.',
            'anio.string' => 'El año escolar debe ser texto.',
            'anio.max' => 'El año escolar no debe exceder 10 caracteres.',
            'anio.unique' => 'Este año escolar ya está registrado.',

            'descripcion.max' => 'La descripción no debe exceder 200 caracteres.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser válida.',

            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser válida.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'Estado no válido.',
        ];

        $validatedData = $request->validate([
            'anio' => 'required|string|max:10|unique:anios_escolares',
            'descripcion' => 'nullable|string|max:200',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:Activo,Finalizado',
        ], $messages);

        $anioEscolar = AnioEscolar::create($validatedData);
        
        Log::info("Nuevo año escolar creado", ['anio_escolar' => $anioEscolar]);

        return redirect()->route('anios-escolares.index')
            ->with('success', 'Año escolar creado con éxito');

    } catch (ValidationException $e) {
        return redirect()->back()
            ->withInput()
            ->withErrors($e->validator);

    } catch (Exception $e) {
        Log::error("Error al crear año escolar", ['error' => $e->getMessage()]);
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al crear el año escolar: ' . $e->getMessage());
    }
}
    
public function edit($id)
{
    $anio = AnioEscolar::findOrFail($id);
    return view('pages.admin.anios_escolares.edit', [
        'anio' => $anio
    ]);
}
public function update(Request $request, $id)
{
    try {
        $anio = AnioEscolar::findOrFail($id);

        $messages = [
            'anio.required' => 'El año escolar es obligatorio.',
            'anio.string' => 'El año escolar debe ser texto.',
            'anio.max' => 'El año escolar no debe exceder 10 caracteres.',
            'anio.unique' => 'Este año escolar ya está registrado.',

            'descripcion.max' => 'La descripción no debe exceder 200 caracteres.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser válida.',

            'fecha_fin.required' => 'La fecha de fin es obligatoria.',
            'fecha_fin.date' => 'La fecha de fin debe ser válida.',
            'fecha_fin.after' => 'La fecha de fin debe ser posterior a la de inicio.',

            'estado.required' => 'El estado es obligatorio.',
            'estado.in' => 'Estado no válido.',
        ];

        $validatedData = $request->validate([
            'anio' => 'required|string|max:10|unique:anios_escolares,anio,'.$anio->id_anio_escolar.',id_anio_escolar',
            'descripcion' => 'nullable|string|max:200',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio',
            'estado' => 'required|in:Activo,Finalizado',
        ], $messages);

        $anio->update($validatedData);
        
        Log::info("Año escolar actualizado", ['anio_escolar' => $anio]);

        return redirect()->route('anios-escolares.index')
            ->with('success', 'Año escolar actualizado con éxito');

    } catch (ValidationException $e) {
        return redirect()->back()
            ->withInput()
            ->withErrors($e->validator);

    } catch (Exception $e) {
        Log::error("Error al actualizar año escolar", ['error' => $e->getMessage()]);
        return redirect()->back()
            ->withInput()
            ->with('error', 'Error al actualizar el año escolar: ' . $e->getMessage());
    }
}

public function destroy($id)
{
    try {
        $anio = AnioEscolar::findOrFail($id);

        // Verificar si el año está en uso antes de eliminar
        if ($anio->estado == 'Activo') {
            throw new Exception('No se puede eliminar un año escolar activo.');
        }

        // Verificar si hay períodos asociados
      /*  if ($anio->periodos()->exists()) {
            throw new Exception('No se puede eliminar porque tiene períodos asociados.');
        } */

        $anio->delete();
        
        Log::info("Año escolar eliminado", ['id_anio_escolar' => $id]);

        return redirect()->route('anios-escolares.index')
            ->with('success', 'Año escolar eliminado con éxito');

    } catch (Exception $e) {
        Log::error("Error al eliminar año escolar", ['error' => $e->getMessage()]);
        return redirect()->back()
            ->with('error', 'Error al eliminar el año escolar: ' . $e->getMessage());
    }
}
}
