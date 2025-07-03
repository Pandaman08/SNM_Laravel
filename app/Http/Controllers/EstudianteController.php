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

class EstudianteController extends Controller
{
    public function showEstudiante(Request $request)
    {
        $query = $request->input('search');

        $estudiantes = Estudiante::when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('persona', function ($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                    ->orWhere('lastname', 'like', '%' . $query . '%');
            });
        })
            ->paginate(10);
        $roles = UserRole::cases();

        return view('pages.admin.estudiantes.index', compact('estudiantes', 'roles'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_name' => 'required|string|max:100',
            'edit_lastname' => 'required|string|max:100',
            'edit_dni' => 'required|string|size:8',
            'edit_address' => 'required|string',
            'edit_fecha_nacimiento' => 'required|date',
            'edit_pais' => 'required|string',
            'edit_provincia' => 'required|string',
            'edit_distrito' => 'required|string',
            'edit_departamento' => 'required|string',
            'edit_lengua_materna' => 'required|string',
            'edit_religion' => 'required|string',
            'edit_photo' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();

        try {
            $estudiante = Estudiante::with('persona')->findOrFail($id);

            // Actualizar datos de persona
            $estudiante->persona->name = $request->edit_name;
            $estudiante->persona->lastname = $request->edit_lastname;
            $estudiante->persona->dni = $request->edit_dni;
            $estudiante->persona->address = $request->edit_address;
            $estudiante->persona->fecha_nacimiento = $request->edit_fecha_nacimiento;

            if ($request->hasFile('edit_photo')) {
                // Eliminar foto anterior si existe
                if ($estudiante->persona->photo) {
                    Storage::disk('public')->delete($estudiante->persona->photo);
                }
                $path = $request->file('edit_photo')->store('profile_photos', 'public');
                $estudiante->persona->photo = $path;
            }

            $estudiante->persona->save();

            // Actualizar datos específicos de estudiante
            $estudiante->pais = $request->edit_pais;
            $estudiante->provincia = $request->edit_provincia;
            $estudiante->distrito = $request->edit_distrito;
            $estudiante->departamento = $request->edit_departamento;
            $estudiante->lengua_materna = $request->edit_lengua_materna;
            $estudiante->religion = $request->edit_religion;
            $estudiante->save();

            DB::commit();

            return redirect()->route('estudiantes.buscar')->with('success', 'Estudiante actualizado correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar estudiante: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
    
        $estudiante = Estudiante::with('persona')->findOrFail($id);

      
        return view('pages.admin.estudiantes.estudiantes.show', compact('estudiante'));
    }

}
