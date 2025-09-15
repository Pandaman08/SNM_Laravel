<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use App\Models\Matricula;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Str;

class PagoController extends Controller
{

    public function index(Request $request)
    {
        $searchTerm = $request->input('buscarpor');
        $userId = auth()->user()->user_id; // Obtener el ID del usuario autenticado
        
        // Obtener el tutor autenticado
        $tutor = \App\Models\Tutor::where('user_id', $userId)->first();
        
        if (!$tutor) {
            // Si no es un tutor, redirigir o mostrar error
            return redirect()->back()->with('error', 'No tienes permisos para ver esta página');
        }
        
        // Obtener los códigos de estudiantes asociados a este tutor
        $codigosEstudiantes = \App\Models\EstudianteTutor::where('id_tutor', $tutor->id_tutor)
            ->pluck('codigo_estudiante')
            ->toArray();
        
        // Obtener las matrículas de estos estudiantes
        $codigosMatriculas = \App\Models\Matricula::whereIn('codigo_estudiante', $codigosEstudiantes)
            ->pluck('codigo_matricula')
            ->toArray();
        
        // Filtrar pagos solo de las matrículas de los estudiantes del tutor
        $pagos = Pago::with('matricula.estudiante.persona')
            ->whereIn('codigo_matricula', $codigosMatriculas)
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where('concepto', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('matricula.estudiante.persona', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%')
                            ->orWhere('lastName', 'like', '%' . $searchTerm . '%');
                    });
            })
            ->orderBy('fecha_pago', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.pagos.index', [
            'pagos' => $pagos,
            'buscarpor' => $searchTerm
        ]);
    }

    public function create($codigo)
    {
        $matricula = Matricula::findOrFail($codigo);
        return view('pages.admin.pagos.create', compact('matricula'));
    }

    public function store(Request $request)
    {
        try {
            $this->handleFileSize();
            $messages = [
                'codigo_matricula.required' => 'La matrícula es obligatoria.',
                'codigo_matricula.exists' => 'La matrícula seleccionada no es válida.',

                'concepto.required' => 'El concepto es obligatorio.',
                'concepto.string' => 'El concepto debe ser texto.',
                'concepto.max' => 'El concepto no debe exceder 100 caracteres.',

                'monto.required' => 'El monto es obligatorio.',
                'monto.numeric' => 'El monto debe ser un número.',
                'monto.min' => 'El monto debe ser al menos 0.',

                'fecha_pago.required' => 'La fecha de pago es obligatoria.',
                'fecha_pago.date' => 'La fecha debe ser válida.',

                'comprobante_img.image' => 'El archivo debe ser una imagen.',
                'comprobante_img.max' => 'La imagen no debe exceder 4MB.',
                'comprobante_img.mimes' => 'La imagen debe ser JPG, PNG o JPEG.',


            ];

            $validatedData = $request->validate([
                'codigo_matricula' => 'required|exists:matriculas,codigo_matricula',
                'concepto' => 'required|string|max:100',
                'monto' => 'required|numeric|min:0',
                'fecha_pago' => 'required|date',
                'comprobante_img' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',

            ], $messages);

            $rutaImagen = null;
            if ($request->hasFile('comprobante_img')) {
                $rutaImagen = $request->file('comprobante_img')->store('comprobantes', 'public');
            }

            $pago = Pago::create([
                'codigo_matricula' => $validatedData['codigo_matricula'],
                'concepto' => $validatedData['concepto'],
                'monto' => $validatedData['monto'],
                'fecha_pago' => $validatedData['fecha_pago'],
                'comprobante_img' => $rutaImagen,
                'estado' => 'Pendiente'
            ]);

            Log::info("Nuevo pago registrado", ['pago' => $pago]);

            return redirect()->route('matriculas.mis-matriculas')
                ->with('success', 'Pago registrado con éxito');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            Log::error("Error al registrar pago", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el pago: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pago = Pago::findOrFail($id);
        $matriculas = Matricula::with('estudiante')->get();

        return view('pages.admin.pagos.edit', [
            'pago' => $pago,
            'matriculas' => $matriculas
        ]);
    }
    public function show($id_pago)
    {
        $pago = Pago::with([
            'matricula.estudiante.persona',
            'matricula.seccion.grado.nivelEducativo'
        ])->findOrFail($id_pago);

        return view('pages.admin.pagos.show', compact('pago'));
    }

    public function update(Request $request, $id)
    {
        try {
            $this->handleFileSize();
            $pago = Pago::findOrFail($id);

            $messages = [
                'codigo_matricula.required' => 'La matrícula es obligatoria.',
                'codigo_matricula.exists' => 'La matrícula seleccionada no es válida.',

                'concepto.required' => 'El concepto es obligatorio.',
                'concepto.string' => 'El concepto debe ser texto.',
                'concepto.max' => 'El concepto no debe exceder 100 caracteres.',

                'monto.required' => 'El monto es obligatorio.',
                'monto.numeric' => 'El monto debe ser un número.',
                'monto.min' => 'El monto debe ser al menos 0.',

                'fecha_pago.required' => 'La fecha de pago es obligatoria.',
                'fecha_pago.date' => 'La fecha debe ser válida.',

                'comprobante_img.image' => 'El archivo debe ser una imagen.',
                'comprobante_img.max' => 'La imagen no debe exceder 4MB.',
                'comprobante_img.mimes' => 'La imagen debe ser JPG, PNG o JPEG.',

                'estado.required' => 'El estado es obligatorio.',
                'estado.in' => 'Estado no válido.',
            ];

            $validatedData = $request->validate([
                'codigo_matricula' => 'required|exists:matriculas,codigo_matricula',
                'concepto' => 'required|string|max:100',
                'monto' => 'required|numeric|min:0',
                'fecha_pago' => 'required|date',
                'comprobante_img' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
                'estado' => 'required|in:Pendiente,Finalizado',
            ], $messages);

            // Manejo de la imagen
            if ($request->hasFile('comprobante_img')) {
                // Eliminar imagen anterior si existe
                if ($pago->comprobante_img && Storage::disk('public')->exists($pago->comprobante_img)) {
                    Storage::disk('public')->delete($pago->comprobante_img);
                }
                $validatedData['comprobante_img'] = $request->file('comprobante_img')->store('comprobantes', 'public');
            } else {
                unset($validatedData['comprobante_img']); // No actualizar la imagen si no se subió una nueva
            }

            $pago->update($validatedData);

            Log::info("Pago actualizado", ['pago' => $pago]);

            return redirect()->route('pagos.index')
                ->with('success', 'Pago actualizado con éxito');

        } catch (ValidationException $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors($e->validator);

        } catch (Exception $e) {
            Log::error("Error al actualizar pago", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el pago: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pago = Pago::findOrFail($id);

            // Eliminar imagen si existe
            if ($pago->comprobante_img && Storage::disk('public')->exists($pago->comprobante_img)) {
                Storage::disk('public')->delete($pago->comprobante_img);
            }

            $pago->delete();

            Log::info("Pago eliminado", ['id_pago' => $id]);

            return redirect()->route('pagos.index')
                ->with('success', 'Pago eliminado con éxito');

        } catch (Exception $e) {
            Log::error("Error al eliminar pago", ['error' => $e->getMessage()]);
            return redirect()->back()
                ->with('error', 'Error al eliminar el pago: ' . $e->getMessage());
        }
    }

    public function handleFileSize()
    {
        ini_set('post_max_size', '10M');
        ini_set('upload_max_filesize', '10M');
        ini_set('max_execution_time', '300');
    }
}
