<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodo;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;
class PeriodoController extends Controller
{



    public function index(Request $request)
    {
        $searchTerm = $request->input('buscarpor');

        $periodos = Periodo::query()
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->Where('nombre', 'like', '%' . $searchTerm . '%');
            })
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.periodos.index', [
            'periodos' => $periodos,
            'buscarpor' => $searchTerm
        ]);
    }


    public function store(Request $request)
    {
        try {
            $messages = [
                'nombre.required' => 'El nombre del período es obligatorio.',
                'nombre.string' => 'El nombre del período debe ser texto.',
                'nombre.max' => 'El nombre del período no debe exceder 100 caracteres.',
                'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
                'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
                'fecha_fin.required' => 'La fecha de fin es obligatoria.',
                'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
                'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            ];

            $validatedData = $request->validate([
                'nombre' => 'required|string|max:100',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',
            ], $messages);

            Periodo::create([
                'nombre' => $validatedData['nombre'],
                'fecha_inicio' => $validatedData['fecha_inicio'],
                'fecha_fin' => $validatedData['fecha_fin'],
            ]);

            return redirect()->route('periodos.index')->with('success', 'Período creado con éxito');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            Log::error("Error al crear período: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el período: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $periodo = Periodo::findOrFail($id);

            $messages = [
                'nombre.required' => 'El nombre del período es obligatorio.',
                'nombre.string' => 'El nombre debe ser texto.',
                'nombre.max' => 'El nombre no debe exceder 100 caracteres.',
                'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
                'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
                'fecha_fin.required' => 'La fecha de fin es obligatoria.',
                'fecha_fin.date' => 'La fecha de fin debe ser una fecha válida.',
                'fecha_fin.after' => 'La fecha de fin debe ser posterior a la fecha de inicio.',
            ];

            $validatedData = $request->validate([
                'nombre' => 'required|string|max:100',
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after:fecha_inicio',
            ], $messages);

            $periodo->update($validatedData);

            return redirect()->route('periodos.index')->with('success-update', 'Período actualizado exitosamente.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            Log::error("Error al actualizar período: " . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el período: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $periodo = Periodo::findOrFail($id);

            $periodo->delete();

            return redirect()->route('periodos.index')->with('success-destroy', 'Período eliminado exitosamente.');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el período: ' . $e->getMessage());
        }
    }
    public function create()
    {
        return view('pages.admin.periodos.create');
    }

    public function edit($id)
    {
        $periodo = Periodo::find($id);

        if (!$periodo) {
            return redirect()->route('periodos.index')
                ->with('error', 'Paper no encontrado');
        }



        return view('pages.admin.periodos.edit', [
            'periodo' => $periodo,
        ]);
    }
}
