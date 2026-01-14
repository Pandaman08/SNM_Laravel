<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Periodo;
use App\Models\AnioEscolar;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\Log;
class PeriodoController extends Controller
{



    public function index(Request $request)
    {
        $searchTerm = $request->input('buscarpor');
        
        // Obtener el año escolar activo
        $anioActivo = AnioEscolar::where('estado', 'Activo')->first();

        // Si hay año activo, filtrar periodos de ese año
        $query = Periodo::with('anioEscolar');

        if ($anioActivo) {
            $query->where('id_anio_escolar', $anioActivo->id_anio_escolar);
        } else {
            // Si no hay año activo, no mostrar periodos (o mostrar mensaje en vista)
            $query->whereRaw('1 = 0');
        }

        $periodos = $query->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('nombre', 'like', '%' . $searchTerm . '%');
            })
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.periodos.index', [
            'periodos' => $periodos,
            'buscarpor' => $searchTerm,
            'anioActivo' => $anioActivo
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
                'id_anio_escolar' => 'required|exists:anios_escolares,id_anio_escolar',
            ], $messages);

            Periodo::create([
                'nombre' => $validatedData['nombre'],
                'fecha_inicio' => $validatedData['fecha_inicio'],
                'fecha_fin' => $validatedData['fecha_fin'],
                'id_anio_escolar' => $validatedData['id_anio_escolar'],
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
        $anioActual = AnioEscolar::where('estado', 'Activo')->first();
        return view('pages.admin.periodos.create', compact('anioActual'));
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
