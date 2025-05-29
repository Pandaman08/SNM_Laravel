<?php

namespace App\Http\Controllers;
use App\Models\Pago;
use App\Models\Matricula;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;

class MatriculaController extends Controller
{
    public function store(Request $request)
    {
        try {
            //
            $messages = [
                'id_tipo_matricula.required' => 'El campo monto es obligatorio.',
                'fecha.required' => 'El campo fecha es obligatorio.',
                'fecha.date' => 'El campo fecha debe ser una fecha válida.',
                'concepto.required' => 'El campo concepto es requerido.',
                'concepto.string' => 'El campo concepto debe ser una cadena de texto.',
                'concepto.max' => 'El campo concepto no debe exceder 100.',
                'monto.required' => 'El campo monto es obligatorio.',
                'fecha_pago.date' => 'El campo fecha debe ser una fecha válida.',
                'comprobante_img.image' => 'El archivo debe ser una imagen.',
                'comprobante_img.max' => 'La imagen no debe exceder los 4MB.',
                'comprobante_img.mimes' => 'La imagen debe ser de tipo JPG, PNG o JPEG.',
            ];

            $request->validate([
                'concepto' => 'required|string|max:100',
                'monto' => 'required|float',
                'id_tipo_matricula' => 'required',
                'fecha' => 'required|date',
                'fecha_pago' => 'required|date',
                'comprobante_img' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
            ], $messages);

            $rutaImagen = null;
            if ($request->hasFile('comprobante_img')) {
                $imagen = $request->file('comprobante_img');
                $rutaImagen = $imagen->store('upload/pagos', 'public'); // Guardar imagen en el directorio public
            }


            $matricula = Matricula::create([
                'codigo_estudiante' => $request->codigo_estudiante,
                'id_anio_escolar' => $request->contenido,
                'id_tipo_matricula' => $request->autor,
                'fecha' => $request->fecha,
                'estado' => false
            ]);

            $pago = Pago::create([

                'codigo_matricula' => $matricula->codigo_matricula,
                'concepto' => $request->concepto,
                'monto' => $request->monto,
                'fecha_pago' => $request->fecha_pago,
                'comprobante_img' => $rutaImagen,
                'estado' => 'Pendiente'
            ]);





            return redirect()->route('matriculas.index')->with('success', 'Matricula registrada con éxito');
        } catch (ValidationException $e) {
            $errorMessage = implode('<br>', $e->validator->errors()->all());
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Error: ' . 'Hubo un error. Porfavor, pruebe denuevo');
        }
    }

    public function index()
    {

    }

}
