<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Persona;
use App\Models\Secretaria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Enums\UserRole;
use Illuminate\Support\Str;
class SecretariaController extends Controller
{


    public function showTesoreros(Request $request)
    {
        $query = $request->input('search');

        $users = User::where('rol', '=', 'secretaria')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->Where('email', 'like', '%' . $query . '%');
                });
            })
            ->paginate(10);
        $roles = UserRole::cases();

        return view('pages.admin.tesoreras.index', compact('users', 'roles'));
    }
    public function index(Request $request)
    {
        $query = $request->input('search');

        $users = User::with(['persona', 'secretaria'])
            ->where('rol', '=', 'secretaria')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->whereHas('persona', function ($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%")
                            ->orWhere('lastname', 'like', "%{$query}%");
                    })->orWhere('email', 'like', "%{$query}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('pages.admin.tesoreras.index', compact('users'));
    }
/*
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'dni' => 'required|string|size:8',
            'phone' => 'required|string|max:15',
            'sexo' => 'required|in:M,F',
            'address' => 'required|string|max:200',
            'fecha_nacimiento' => 'required|date',
            'area_responsabilidad' => 'required|string|max:100',
            'jornada_laboral' => 'required|numeric|min:1|max:48',
            'fecha_contratacion' => 'required|date|after_or_equal:fecha_nacimiento',
            'photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg|dimensions:min_width=100,min_height=100',
        ]);

        DB::beginTransaction();

        try {
            $email = $this->generateUniqueEmail(
                $validated['name'],
                $validated['lastname'],
                'bruning.com'
            );

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')
                    ->storeAs(
                        'profile_photos',
                        'secretaria_' . time() . '.' . $request->file('photo')->extension(),
                        'public'
                    );
            }

            $persona = Persona::create([
                'name' => $validated['name'],
                'lastname' => $validated['lastname'],
                'dni' => $validated['dni'],
                'phone' => $validated['phone'],
                'sexo' => $validated['sexo'],
                'address' => $validated['address'],
                'fecha_nacimiento' => $validated['fecha_nacimiento'],
                'photo' => $photoPath
            ]);


            $user = User::create([
                'persona_id' => $persona->persona_id,
                'email' => $email,
                'password' => Hash::make('password'),
                'rol' => 'secretaria',
                'estado' => true,
                'email_verified_at' => now()
            ]);

            $secretaria = Secretaria::create([
                'user_id' => $user->user_id,
                'area_responsabilidad' => $validated['area_responsabilidad'],
                'jornada_laboral' => $validated['jornada_laboral'],
                'fecha_contratacion' => $validated['fecha_contratacion'],
            ]);

            DB::commit();



            return redirect()
                ->route('secretarias.buscar')
                ->with([
                    'success' => 'Secretaria creada exitosamente',
                ]);

        } catch (\Exception $e) {

            if (isset($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            Log::error('Error al crear secretaria', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input' => $request->all()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Error al crear secretaria: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $user_id)
    {
        $user = User::with(['persona', 'secretaria'])->findOrFail($user_id);

        $validated = $request->validate([
            'edit_name' => 'required|string|max:100',
            'edit_lastname' => 'required|string|max:100',
            'edit_dni' => 'required|string|size:8',
            'edit_phone' => 'required|string|max:15',
            'edit_sexo' => 'required|in:M,F',
            'edit_address' => 'required|string|max:200',
            'edit_fecha_nacimiento' => 'required|date',
            'edit_especialidad' => 'required|string|max:100',
            'edit_jornada_laboral' => 'required|numeric',
            'edit_departamento_estudios' => 'required|string|max:100',
            'edit_fecha_contratacion' => 'required|date',
            'edit_photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
        ]);

        DB::beginTransaction();

        try {
            $personaData = [
                'name' => $validated['edit_name'],
                'lastname' => $validated['edit_lastname'],
                'dni' => $validated['edit_dni'],
                'phone' => $validated['edit_phone'],
                'sexo' => $validated['edit_sexo'],
                'address' => $validated['edit_address'],
                'fecha_nacimiento' => $validated['edit_fecha_nacimiento'],
            ];

            $oldPhotoPath = $user->persona->photo;
            if ($request->hasFile('edit_photo')) {
                $personaData['photo'] = $request->file('edit_photo')
                    ->storeAs(
                        'profile_photos',
                        'secretaria_' . time() . '.' . $request->file('edit_photo')->extension(),
                        'public'
                    );
            }

            $user->persona->update($personaData);

            $user->secretaria->update([
                'area_responsabilidad' => $validated['edit_area_responsabilidad'],
                'jornada_laboral' => $validated['edit_jornada_laboral'],
                'fecha_contratacion' => $validated['edit_fecha_contratacion'],
            ]);

            DB::commit();

            if ($request->hasFile('edit_photo') && $oldPhotoPath) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            return redirect()
                ->route('secretarias.buscar')
                ->with('success-update', 'Secretaria actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($personaData['photo'])) {
                Storage::disk('public')->delete($personaData['photo']);
            }
            Log::error('Error al actualizar secretaria', [
                'user_id' => $user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->withInput()
                ->with('error', 'Error al actualizar secretaria: ' . $e->getMessage());
        }
    }

    public function destroy($user_id)
    {
        $user = User::with(['persona', 'secretaria'])->findOrFail($user_id);

        DB::beginTransaction();

        try {
            $photoPath = $user->persona->photo;
            $secretariaId = $user->secretaria->codigo_secretaria;
            $personaId = $user->persona->id;

            $user->secretaria->delete();
            $user->delete();
            $user->persona->delete();

            DB::commit();

            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            Log::info('Secretaria eliminada', [
                'secretaria_id' => $secretariaId,
                'user_id' => $user_id,
                'persona_id' => $personaId,
                'deleted_by' => auth()->id()
            ]);

            return redirect()
                ->route('secretarias.buscar')
                ->with('success-destroy', 'Secretaria eliminada exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar secretaria', [
                'user_id' => $user_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()
                ->with('error', 'No se pudo eliminar la secretaria: ' . $e->getMessage());
        }
    }

    private function generateUniqueEmail($name, $lastname, $domain)
    {
        $base = strtolower($name) . '.' . strtolower($lastname);
        $email = $base . '@' . $domain;
        $counter = 1;

        while (User::where('email', $email)->exists()) {
            $email = $base . $counter . '@' . $domain;
            $counter++;
        }

        return $email;
    }
        */
        public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'dni' => 'required|string|size:8|unique:personas,dni',
            'phone' => 'required|string|max:9',
            'sexo' => 'required|in:M,F',
            'address' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'area_responsabilidad' => 'required|string',
            'jornada_laboral' => 'required|numeric',
            'fecha_contratacion' => 'required|date',
            'photo' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();

        try {
            // Crear persona
            $persona = new Persona();
            $persona->name = $request->name;
            $persona->lastname = $request->lastname;
            $persona->dni = $request->dni;
            $persona->phone = $request->phone;
            $persona->sexo = $request->sexo;
            $persona->address = $request->address;
            $persona->fecha_nacimiento = $request->fecha_nacimiento;
            
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('profile_photos', 'public');
                $persona->photo = $path;
            }

            $persona->save();

            // Crear usuario
            $user = new User();
            $user->persona_id = $persona->persona_id;
            $user->email = strtolower($request->name[0]) . strtolower($request->lastname) . rand(100, 999) . '@institucion.edu.pe';
            $user->password = Hash::make('password');
            $user->rol = 'secretaria';
            $user->estado = true;
            $user->save();

            // Crear secretaria
            $secretaria = new Secretaria();
            $secretaria->user_id = $user->user_id;
            $secretaria->area_responsabilidad = $request->area_responsabilidad;
            $secretaria->jornada_laboral = $request->jornada_laboral;
            $secretaria->fecha_contratacion = $request->fecha_contratacion;
            $secretaria->save();

            DB::commit();

            return redirect()->route('secretarias.buscar')->with('success', 'Secretaria registrada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar secretaria: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'edit_name' => 'required|string|max:50',
            'edit_lastname' => 'required|string|max:50',
            'edit_dni' => 'required|string|size:8',
            'edit_phone' => 'required|string|max:9',
            'edit_sexo' => 'required|in:M,F',
            'edit_address' => 'required|string',
            'edit_fecha_nacimiento' => 'required|date',
            'edit_area_responsabilidad' => 'required|string',
            'edit_jornada_laboral' => 'required|numeric',
            'edit_fecha_contratacion' => 'required|date',
            'edit_photo' => 'nullable|image|max:2048'
        ]);

        DB::beginTransaction();

        try {
            $user = User::with(['persona', 'secretaria'])->findOrFail($id);

            // Actualizar persona
            $user->persona->name = $request->edit_name;
            $user->persona->lastname = $request->edit_lastname;
            $user->persona->dni = $request->edit_dni;
            $user->persona->phone = $request->edit_phone;
            $user->persona->sexo = $request->edit_sexo;
            $user->persona->address = $request->edit_address;
            $user->persona->fecha_nacimiento = $request->edit_fecha_nacimiento;

            if ($request->hasFile('edit_photo')) {
                // Eliminar foto anterior si existe
                if ($user->persona->photo) {
                    Storage::disk('public')->delete($user->persona->photo);
                }
                $path = $request->file('edit_photo')->store('profile_photos', 'public');
                $user->persona->photo = $path;
            }

            $user->persona->save();

            // Actualizar secretaria
            $user->secretaria->area_responsabilidad = $request->edit_area_responsabilidad;
            $user->secretaria->jornada_laboral = $request->edit_jornada_laboral;
            $user->secretaria->fecha_contratacion = $request->edit_fecha_contratacion;
            $user->secretaria->save();

            DB::commit();

            return redirect()->route('secretarias.buscar')->with('success', 'Secretaria actualizada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar secretaria: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $user = User::with(['persona', 'secretaria'])->findOrFail($id);

            // Eliminar foto si existe
            if ($user->persona->photo) {
                Storage::disk('public')->delete($user->persona->photo);
            }

            $user->secretaria->delete();
            $user->delete();
            $user->persona->delete();

            DB::commit();

            return redirect()->route('secretarias.buscar')->with('success', 'Secretaria eliminada correctamente');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar secretaria: ' . $e->getMessage());
        }
    }
}
