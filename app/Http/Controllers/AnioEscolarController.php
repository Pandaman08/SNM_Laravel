<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use App\Models\AnioEscolar;
class AnioEscolarController extends Controller
{
     public function store(Request $request){
          try {

            $messages = [
                'nombre.unique' => 'El nombre ya esta registrado.',
            ];

            // validar campos
            $request->validate([
                'nombre' => 'required|string|max:40',

            ],$messages);



            $anio = AnioEscolar::create($request->all());

           // Log::info("Stored successfully", ['topico' => $topico]);

            return redirect()->route('index')
                ->with('success', 'Nuevo Añio escolar registrado.');
            } catch (ValidationException $e) {
                $errorMessage = implode('<br>', $e->validator->errors()->all());
                return redirect()->back()
                    ->withInput()
                    ->with('error', $errorMessage);
        } catch (Exception $e) {
           // Log::error("Error storing: " . $e->getMessage());

            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }

    }



}
