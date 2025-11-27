<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Persona;
use Illuminate\Support\Facades\Storage;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if (!$user->estado) {
                Auth::logout();
                return back()->withErrors(['email' => 'Tu cuenta aún no ha sido aprobada por un administrador.'])->withInput();
            }

            return $this->redirectToRoleDashboard($user->rol);
        }

        return back()->withErrors(['email' => 'Las credenciales no coinciden'])->withInput();
    }

    protected function redirectToRoleDashboard(UserRole $role)
    {
        $route = $role->dashboardRoute(); // Using the enum method we'll create

        return redirect()->route($route)
            ->with('success', 'Sesión iniciada correctamente');
    }

    public function logout()
    {
        Auth::logout();

        return view('auth.logout');
    }

    public function showUser(Request $request)
    {
        $query = $request->input('search');
        $roleFilter = $request->input('role');
        $sexoFilter = $request->input('sexo');
        $estadoCivilFilter = $request->input('estado_civil');
        $edadMin = $request->input('edad_min');
        $edadMax = $request->input('edad_max');
        $orderBy = $request->input('order_by', 'created_at');
        $orderDirection = $request->input('order_direction', 'desc');


        $users = User::with('persona')
            ->where('estado', true)
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->where('email', 'like', '%' . $query . '%')
                        ->orWhereHas('persona', function ($personaQuery) use ($query) {
                            $personaQuery->where('name', 'like', '%' . $query . '%')
                                ->orWhere('lastname', 'like', '%' . $query . '%')
                                ->orWhere('dni', 'like', '%' . $query . '%')
                                ->orWhere('phone', 'like', '%' . $query . '%');
                        });
                });
            })
            ->when($roleFilter, function ($queryBuilder) use ($roleFilter) {
                $queryBuilder->where('rol', $roleFilter);
            })
            ->when($sexoFilter, function ($queryBuilder) use ($sexoFilter) {
                $queryBuilder->whereHas('persona', function ($personaQuery) use ($sexoFilter) {
                    $personaQuery->where('sexo', $sexoFilter);
                });
            })
            ->when($estadoCivilFilter, function ($queryBuilder) use ($estadoCivilFilter) {
                $queryBuilder->whereHas('persona', function ($personaQuery) use ($estadoCivilFilter) {
                    $personaQuery->where('estado_civil', $estadoCivilFilter);
                });
            })
            ->when($edadMin || $edadMax, function ($queryBuilder) use ($edadMin, $edadMax) {
                $queryBuilder->whereHas('persona', function ($personaQuery) use ($edadMin, $edadMax) {
                    if ($edadMin) {
                        $fechaMaxima = now()->subYears($edadMin)->format('Y-m-d');
                        $personaQuery->where('fecha_nacimiento', '<=', $fechaMaxima);
                    }
                    if ($edadMax) {
                        $fechaMinima = now()->subYears($edadMax + 1)->addDay()->format('Y-m-d');
                        $personaQuery->where('fecha_nacimiento', '>=', $fechaMinima);
                    }
                });
            })
            ->when($orderBy === 'name', function ($queryBuilder) use ($orderDirection) {
                $queryBuilder->join('personas', 'users.persona_id', '=', 'personas.persona_id')
                    ->orderBy('personas.name', $orderDirection)
                    ->select('users.*');
            })
            ->when($orderBy === 'email', function ($queryBuilder) use ($orderDirection) {
                $queryBuilder->orderBy('email', $orderDirection);
            })
            ->when($orderBy === 'role', function ($queryBuilder) use ($orderDirection) {
                $queryBuilder->orderBy('rol', $orderDirection);
            })
            ->when($orderBy === 'created_at', function ($queryBuilder) use ($orderDirection) {
                $queryBuilder->orderBy('created_at', $orderDirection);
            })
            ->paginate(10)
            ->appends($request->all());
        $roles = UserRole::cases();

        return view('pages.admin.show', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        try {
            $messages = [
                'name.required' => 'El nombre es obligatorio.',
                'name.string' => 'El nombre debe ser texto.',
                'name.max' => 'El nombre no debe exceder los 100 caracteres.',
                'lastname.required' => 'El apellido es obligatorio.',
                'lastname.string' => 'El apellido debe ser texto.',
                'lastname.max' => 'El apellido no debe exceder los 100 caracteres.',
                'dni.required' => 'El DNI es obligatorio.',
                'dni.string' => 'El DNI debe ser texto.',
                'dni.size' => 'El DNI debe tener exactamente 8 caracteres.',
                'phone.required' => 'El teléfono es obligatorio.',
                'phone.max' => 'El teléfono no debe exceder los 9 caracteres.',
                'sexo.required' => 'El sexo es obligatorio.',
                'sexo.in' => 'El sexo debe ser M (Masculino) o F (Femenino).',
                'estado_civil.required' => 'El estado civil es obligatorio.',
                'estado_civil.in' => 'El estado civil debe ser S (Soltero), C (Casado), D (Divorciado) o V (Viudo).',
                'address.required' => 'La dirección es obligatoria.',
                'address.string' => 'La dirección debe ser texto.',
                'address.max' => 'La dirección no debe exceder los 200 caracteres.',
                'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'Debe ingresar una fecha válida.',
                'photo.image' => 'El archivo debe ser una imagen.',
                'photo.max' => 'La imagen no debe exceder los 4MB.',
                'photo.mimes' => 'La imagen debe ser JPG, PNG o JPEG.',
            ];

            $request->validate([
                'name' => 'required|string|max:100',
                'lastname' => 'required|string|max:100',
                'dni' => 'required|string|size:8',
                'phone' => 'required|max:9',
                'sexo' => 'required|in:M,F',
                'estado_civil' => 'required|in:S,C,D,V',
                'address' => 'required|string|max:200',
                'fecha_nacimiento' => 'required|date',
                'photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
            ], $messages);

            // Generar email automático
            $nombre = strtolower($request->name);
            $apellido = strtolower($request->lastname);
            $randomNumbers = rand(1000, 9999);
            $email = $nombre . substr($apellido, 0, 1) . '_' . $randomNumbers . '@bruning.com';

            // Crear Persona primero
            $persona = Persona::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'dni' => $request->dni,
                'phone' => $request->phone,
                'sexo' => $request->sexo,
                'estado_civil' => $request->estado_civil,
                'address' => $request->address,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'photo' => $request->hasFile('photo')
                    ? $request->file('photo')->store('profile_photos', 'public')
                    : null
            ]);

            // Crear User asociado con valores fijos
            $user = User::create([
                'persona_id' => $persona->persona_id,
                'email' => $email,
                'password' => Hash::make('password'), // Contraseña fija
                'rol' => 'admin', // Rol fijo como admin
                'estado' => true
            ]);

            return redirect()->route('users.buscar')->with('success', 'Usuario creado exitosamente. Email generado: ' . $email);
        } catch (ValidationException $e) {
            $errorMessage = implode('<br>', $e->validator->errors()->all());
            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        } catch (Exception $e) {
            Log::error("Error storing: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . 'Hubo un error. Porfavor, pruebe denuevo');
        }
    }
    public function edit($id)
    {
        $user = User::with('persona')->findOrFail($id);
        $roles = UserRole::cases();
        return view('pages.admin.users.edit', compact('user', 'roles'));
    }
    /*
        public function update(Request $request, $user_id)
        {
            $user = User::with('persona')->findOrFail($user_id);

            $request->validate([
                'name' => 'required|string|max:100',
                'lastname' => 'required|string|max:100',
                'dni' => 'required|string|size:8',
                'phone' => 'required|string|max:15',
                'sexo' => 'required|in:M,F',
                'estado_civil' => 'required|in:S,C,D,V',
                'address' => 'required|string|max:200',
                'fecha_nacimiento' => 'required|date',
                'email' => 'required|email|unique:users,email,' . $user->user_id,
                'password' => 'nullable|min:6|regex:/[A-Z]/|regex:/[a-z]/',
                'photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
                'rol' => 'required|in:' . implode(',', UserRole::values()),
                'estado' => 'required|boolean'
            ]);


            $personaData = [
                'name' => $request->name,
                'lastname' => $request->lastname,
                'dni' => $request->dni,
                'phone' => $request->phone,
                'sexo' => $request->sexo,
                'estado_civil' => $request->estado_civil,
                'address' => $request->address,
                'fecha_nacimiento' => $request->fecha_nacimiento
            ];

            if ($request->hasFile('photo')) {
                // Eliminar foto anterior si existe
                if ($user->persona->photo) {
                    Storage::disk('public')->delete($user->persona->photo);
                }
                $personaData['photo'] = $request->file('photo')->store('profile_photos', 'public');
            }

            $user->persona->update($personaData);

            // Actualizar User
            $userData = [
                'email' => $request->email,
                'rol' => $request->rol,
                'estado' => $request->estado
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);


            return redirect()->route('users')->with('success-update', 'Usuario actualizada con éxito');

        }
            */


    public function update(Request $request, $user_id)
    {
        try {
            $user = User::with('persona')->findOrFail($user_id);

            $request->validate([
                'edit_name' => 'required|string|max:100',
                'edit_lastname' => 'required|string|max:100',
                'edit_dni' => 'required|string|size:8',
                'edit_phone' => 'required|string|max:15',
                'edit_sexo' => 'required|in:M,F',
                'edit_estado_civil' => 'required|in:S,C,D,V',
                'edit_address' => 'required|string|max:200',
                'edit_fecha_nacimiento' => 'required|date',
                'edit_email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
                'edit_photo' => 'nullable|image|max:2048|mimes:jpg,png,jpeg',
            ]);

            // Actualizar Persona
            $personaData = [
                'name' => $request->edit_name,
                'lastname' => $request->edit_lastname,
                'dni' => $request->edit_dni,
                'phone' => $request->edit_phone,
                'sexo' => $request->edit_sexo,
                'estado_civil' => $request->edit_estado_civil,
                'address' => $request->edit_address,
                'fecha_nacimiento' => $request->edit_fecha_nacimiento
            ];

            if ($request->hasFile('edit_photo')) {
                if ($user->persona->photo) {
                    Storage::disk('public')->delete($user->persona->photo);
                }
                $personaData['photo'] = $request->file('edit_photo')->store('profile_photos', 'public');
            }

            $user->persona->update($personaData);

            // Actualizar User (email)
            $user->update([
                'email' => $request->edit_email
            ]);

            return redirect()->route('users.buscar')->with('success-update', 'Usuario actualizado con éxito');
        } catch (Exception $e) {
            Log::error("Error updating: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error: ' . 'Hubo un error. Porfavor, pruebe denuevo');
        }
    }


    public function destroy($user_id)
    {

        DB::beginTransaction();

        try {
            $user = User::with(['persona', 'docente', 'secretaria', 'tutor'])->findOrFail($user_id);

            // 1. Eliminar primero los modelos relacionados más específicos
            if ($user->docente) {
                $user->docente->delete();
            }

            if ($user->secretaria) {
                $user->secretaria->delete();
            }

            if ($user->tutor) {
                $user->tutor->delete();
            }

            // 2. Eliminar archivos asociados
            if ($user->persona && $user->persona->photo) {
                Storage::disk('public')->delete($user->persona->photo);
            }

            // 3. Eliminar el usuario (que tiene la FK a persona)
            $user->delete();

            // 4. Finalmente eliminar la persona
            if ($user->persona) {
                $user->persona->delete();
            }

            DB::commit();

            return redirect()->route('users.buscar')->with('success-destroy', 'Usuario eliminado exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar usuario: ' . $e->getMessage());

            return redirect()->back()->with('error', 'No se pudo eliminar el usuario debido a relaciones existentes. Error: ' . $e->getMessage());
        }
    }

    public function edit_user()
    {
        $user = Auth::user();
        return view('pages.admin.users.profile', compact('user'));
    }

    public function update_user(Request $request, $user_id)
    {
        $user = User::with('persona')->where('user_id', $user_id)->firstOrFail();


        $request->validate([
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'dni' => 'required|string|size:8',
            'phone' => 'required|string|max:15',
            'address' => 'required|string|max:200',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
        ]);

        $personaData = $request->only([
            'name',
            'lastname',
            'dni',
            'phone',
            'address',
        ]);

        if ($request->hasFile('photo') && Storage::disk('public')->exists($user->persona->photo)) {
            if ($user->persona->photo) {
                Storage::disk('public')->delete($user->persona->photo);
            }
            $personaData['photo'] = $request->file('photo')->store('profile_photos', 'public');
        }

        $user->persona->update($personaData);
        $user->update(['email' => $request->email]);

        return redirect()->back()->with('success-user', 'Datos actualizado con éxito');

    }

    public function update_photo(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('photo')) {

            if ($user->photo && Storage::disk('public')->exists($user->persona->photo)) {
                Storage::disk('public')->delete($user->persona->photo);
            }


            $photoPath = $request->file('photo')->store('profile_photos', 'public');


            $user->persona->update(['photo' => $photoPath]);
        }

        return redirect()->back()->with('success-photo', 'Perfil actualizado correctamente');
    }

    public function update_password(Request $request, $id)
    {
        $user = User::findOrFail($id);


        $request->validate([
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($request->input('password')),
        ]);

        return redirect()->back()->with('success-password', 'Contraseña actualizada correctamente');
    }


}
