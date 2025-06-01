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
        ->when($searchTerm, function($query) use ($searchTerm) {
            $query->where('numero_periodo', 'like', '%'.$searchTerm.'%')
                  ->orWhere('nombre', 'like', '%'.$searchTerm.'%');
        })
        ->orderBy('fecha_inicio', 'desc')
        ->paginate(10)
        ->withQueryString();

    return view('pages.admin.periodos.index', [
        'periodos' => $periodos,
        'buscarpor' => $searchTerm
    ]);
    }
 

    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $messages = [
                'numero_periodo.required' => 'El número de período es obligatorio.',
                'numero_periodo.string' => 'El número de período debe ser texto.',
                'numero_periodo.max' => 'El número de período no debe exceder 255 caracteres.',

                'nombre.required' => 'El nombre del período es obligatorio.',
                'nombre.string' => 'El nombre del período debe ser texto.',
                'nombre.max' => 'El nombre del período no debe exceder 1000 caracteres.',

                'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
                'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',

                'fecha_final.required' => 'La fecha final es obligatoria.',
                'fecha_final.date' => 'La fecha final debe ser una fecha válida.',
                'fecha_final.after' => 'La fecha final debe ser posterior a la fecha de inicio.',

                'estado.required' => 'El estado del período es obligatorio.',
                'estado.in' => 'El estado debe ser uno de los valores permitidos.',
            ];

            $validatedData = $request->validate([
                'numero_periodo' => 'required|max:255',
                'nombre' => 'required|string|max:100',
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date|after:fecha_inicio',
                'estado' => 'required|in:No Iniciado,Proceso,Finalizado',
            ], $messages);

            $PERIODO =Periodo::create([
                'numero_periodo' => $validatedData['numero_periodo'],
                'nombre' => $validatedData['nombre'],
                'fecha_inicio' => $validatedData['fecha_inicio'],
                'fecha_final' => $validatedData['fecha_final'],
                'estado' => $validatedData['estado'],
            ]);
               Log::info("info ", ['persona' => $PERIODO]);
            return redirect()->route('periodos.index')->with('success', 'Período creado con éxito');

        } catch (ValidationException $e) {
               Log::info("error ", ['persona' => $PERIODO]);
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
               Log::info("ierror ");
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al crear el período: ' . $e->getMessage());
        }
    }
    /**
     * Display the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $periodo = Periodo::findOrFail($id);

            $messages = [
                'numero_periodo.required' => 'El número de período es obligatorio.',
                'numero_periodo.max' => 'El número no debe exceder 255 caracteres.',
                'numero_periodo.unique' => 'Este número ya está en uso.',

                'nombre.required' => 'El nombre del período es obligatorio.',
                'nombre.string' => 'El nombre debe ser texto.',
                'nombre.max' => 'El nombre no debe exceder 1000 caracteres.',

                'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
                'fecha_inicio.date' => 'Debe ser una fecha válida.',

                'fecha_final.required' => 'La fecha final es obligatoria.',
                'fecha_final.date' => 'Debe ser una fecha válida.',
                'fecha_final.after' => 'La fecha final debe ser posterior a la de inicio.',

                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'Estado no válido.',
            ];

            $validatedData = $request->validate([
                'numero_periodo' => 'required|max:255|unique:periodos,numero_periodo,' . $periodo->id_periodo,
                'nombre' => 'required|string|max:1000',
                'fecha_inicio' => 'required|date',
                'fecha_final' => 'required|date|after:fecha_inicio',
                'estado' => 'required|in:No Iniciado,Proceso,Finalizado',
            ], $messages);

            $periodo->update($validatedData);

            return redirect()->route('periodos.index')->with('success-update', 'Período actualizado exitosamente.');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el período: ' . $e->getMessage());
        }
    }
    public function destroy($id)
    {
        try {
            $periodo = Periodo::findOrFail($id);

            // Verificar si el período está en uso antes de eliminar
            if ($periodo->estado == 'Proceso') {
                throw new Exception('No se puede eliminar un período activo.');
            }

            $periodo->delete();

            return redirect()->route('periodos.index')->with('success-destroy', 'Período eliminado exitosamente.');

        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al eliminar el período: ' . $e->getMessage());
        }
    }
    public function create(){
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
