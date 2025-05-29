<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pago;
use Illuminate\Validation\ValidationException;
use Exception;

class PagoController extends Controller
{
    public function store(Request $request)
    {
        try {
            //
            $messages = [
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
                'fecha_pago' => 'required|date',
                'comprobante_img' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
            ], $messages);

            $rutaImagen = null;
            if ($request->hasFile('comprobante_img')) {
                $imagen = $request->file('comprobante_img');
                $rutaImagen = $imagen->store('imagenes', 'public'); // Guardar imagen en el directorio public
            }


            Pago::create([
                'id_pago' => $request->titulo,
                'codigo_matricula' => $request->subtitulo,
                'concepto' => $request->contenido,
                'monto' => $request->autor,
                'fecha_pago' => $request->fecha,
                'comprobante_img' => $rutaImagen,
                'estado' => $request->estado
            ]);

            return redirect()->route('pago')->with('success', 'Noticia creada con éxito');
        } catch (ValidationException $e) {
            $errorMessage = implode('<br>', $e->validator->errors()->all());
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        } catch (Exception $e) {

            return redirect()->back()->with('error', 'Error: ' . 'Hubo un error. Porfavor, pruebe denuevo');
        }
    }

    public function show($id)
    {
        //
        $noticia = Pago::findOrFail($id);
        return view('usuario.novedades.detalle-noticias', compact('noticia'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_titulo' => 'required|string|max:255',
            'edit_subtitulo' => 'required|string|max:1000',
            'edit_contenido' => 'required',
            'edit_autor' => 'required|string|max:255',
            'edit_fecha' => 'required|date',
            'edit_imagen' => 'nullable|image|mimes:jpg,png,jpeg',
        ]);

        $noticia = Pago::findOrFail($id);

        // Actualizar la imagen si se sube una nueva
        if ($request->hasFile('edit_imagen')) {
            $imagenPath = $request->file('edit_imagen')->store('noticias', 'public');
            $noticia->imagen = $imagenPath;
        }

        // Actualizar los demás campos
        $noticia->update([
            'titulo'    => $request->edit_titulo,
            'subtitulo' => $request->edit_subtitulo,
            'contenido' => $request->edit_contenido,
            'autor'     => $request->edit_autor,
            'fecha'     => $request->edit_fecha,
        ]);

        return redirect()->route('pagos.index')->with('edit', 'Noticia actualizada con éxito');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pago = Pago::findOrFail($id);
        $pago->delete();

        return redirect()->route('pagos.index')->with('destroy', 'Noticia eliminada con éxito');
    }
}
