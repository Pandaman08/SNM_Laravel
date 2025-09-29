<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Estudiante;
use App\Enums\UserRole;
use App\Models\Periodo;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 

class EstudianteController extends Controller
{
    public function showEstudiante(Request $request)
    {
        $query = $request->input('search');

        $estudiantes = Estudiante::with([
            'persona',
            'tutores.user.persona' // Cargar tutores con sus usuarios y personas
        ])->when($query, function ($queryBuilder) use ($query) {
            $queryBuilder->whereHas('persona', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('lastname', 'like', '%' . $query . '%')
                    ->orWhere('dni', 'like', '%' . $query . '%');
            });
        })->paginate(10);
        
        $roles = UserRole::cases();

        return view('pages.admin.estudiantes.index', compact('estudiantes', 'roles'));
    }

    // Método para enviar correo al tutor
    public function enviarCorreoTutor(Request $request)
    {
        $request->validate([
            'codigo_estudiante' => 'required|exists:estudiantes,codigo_estudiante',
            'asunto' => 'required|string|max:255',
            'mensaje' => 'required|string',
        ]);

        try {
            $estudiante = Estudiante::with('tutores.user.persona', 'persona')
                ->findOrFail($request->codigo_estudiante);
            
            $tutores = $estudiante->tutores;
            
            if ($tutores->isEmpty()) {
                return back()->with('error', 'Este estudiante no tiene tutores asignados.');
            }

            $nombreEstudiante = $estudiante->persona->name . ' ' . $estudiante->persona->lastname;
            $enviadosExitosos = 0;

            // Datos compartidos para todos los correos
            $fecha = now()->format('d/m/Y H:i');
            $asunto = $request->asunto;
            $mensaje = $request->mensaje;

            foreach ($tutores as $tutor) {
                try {
                    $email = $tutor->user->email;
                    $nombreTutor = $tutor->user->persona->name . ' ' . $tutor->user->persona->lastname;
                    
                    Mail::send('emails.notificacion_tutor', [
                        'nombreTutor' => $nombreTutor,
                        'nombreEstudiante' => $nombreEstudiante,
                        'asunto' => $asunto,
                        'mensaje' => $mensaje,
                        'fecha' => $fecha,  // Ahora está definida antes del loop
                    ], function ($message) use ($email, $asunto) {
                        $message->to($email)
                                ->subject($asunto);
                    });
                    
                    $enviadosExitosos++;
                } catch (\Exception $e) {
                    Log::error('Error enviando correo a tutor: ' . $e->getMessage());
                }
            }

            if ($enviadosExitosos > 0) {
                return back()->with('success', "Correo enviado exitosamente a {$enviadosExitosos} tutor(es).");
            } else {
                return back()->with('error', 'No se pudo enviar el correo a ningún tutor.');
            }

        } catch (\Exception $e) {
            Log::error('Error en enviarCorreoTutor: ' . $e->getMessage());
            return back()->with('error', 'Error al enviar el correo: ' . $e->getMessage());
        }
    }
public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:100',
        'lastname' => 'required|string|max:100',
        'dni' => 'required|string|size:8',
        'sexo' => 'required|in:M,F',
        'address' => 'required|string',
        'fecha_nacimiento' => 'required|date',
        'pais' => 'required|string',
        'provincia' => 'required|string',
        'distrito' => 'required|string',
        'departamento' => 'required|string',
        'lengua_materna' => 'required|string',
        'religion' => 'required|string',
        'photo' => 'nullable|image|max:2048'
    ]);

    DB::beginTransaction();

    try {
        $estudiante = Estudiante::with('persona')->findOrFail($id);

        // Actualizar datos de persona
        $estudiante->persona->name = $request->name;
        $estudiante->persona->lastname = $request->lastname;
        $estudiante->persona->dni = $request->dni;
        $estudiante->persona->sexo = $request->sexo;
        $estudiante->persona->address = $request->address;
        $estudiante->persona->fecha_nacimiento = $request->fecha_nacimiento;

        // Manejar foto
        if ($request->hasFile('photo')) {
            // Eliminar foto anterior si existe
            if ($estudiante->persona->photo) {
                Storage::disk('public')->delete($estudiante->persona->photo);
            }
            $path = $request->file('photo')->store('profile_photos', 'public');
            $estudiante->persona->photo = $path;
        }

        $estudiante->persona->save();

        // Actualizar datos específicos de estudiante
        $estudiante->pais = $request->pais;
        $estudiante->provincia = $request->provincia;
        $estudiante->distrito = $request->distrito;
        $estudiante->departamento = $request->departamento;
        $estudiante->lengua_materna = $request->lengua_materna;
        $estudiante->religion = $request->religion;
        $estudiante->save();

        DB::commit();

        return redirect()->route('estudiantes.buscar')->with('success', 'Estudiante actualizado correctamente');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al actualizar estudiante: ' . $e->getMessage());
        return back()->with('error', 'Error al actualizar estudiante: ' . $e->getMessage());
    }
}

    public function show($id)
    {
    
        $estudiante = Estudiante::with('persona')->findOrFail($id);

      
        return view('pages.admin.estudiantes.estudiantes.show', compact('estudiante'));
    }

}
