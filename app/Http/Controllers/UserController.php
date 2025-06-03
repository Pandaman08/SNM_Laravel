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

            return redirect()->route('home')->with('success', 'Sesión iniciada correctamente');
        }

        return back()->withErrors(['email' => 'Las credenciales no coinciden'])->withInput();
    }

    public function logout()
    {
        Auth::logout();

        return view('auth.logout');
    }

    public function showUser(Request $request)
    {
        $query = $request->input('search');

        $users = User::where('estado', true)
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->Where('email', 'like', '%' . $query . '%');
                });
            })
            ->paginate(10);
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

                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'Debe ingresar un correo electrónico válido.',
                'email.unique' => 'Este correo electrónico ya está registrado.',

                'password.required' => 'La contraseña es obligatoria.',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
                'password.regex' => 'La contraseña debe contener al menos una mayúscula y una minúscula.',

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
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6|regex:/[A-Z]/|regex:/[a-z]/',
                'photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',

            ], $messages);


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

            Log::info("info ", ['persona' => $persona]);
            // Crear User asociado
            $user = User::create([
                'persona_id' => $persona->persona_id,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'rol' => 'admin',
                'estado' => true
            ]);

            Log::info("info ", ['persona' => $user]);
            return redirect()->route('users.buscar')->with('success', 'Usuario creado exitosamente.');
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
            'email' => 'required|email',
            'rol' => 'required|in:' . implode(',', UserRole::values()),
            'photo' => 'nullable|image|max:2048|mimes:jpg,png,jpeg',
        ]);

        // Actualizar Persona
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
        $user->update([
            'email' => $request->email,
            'rol' => $request->rol
        ]);

        return redirect()->route('users.buscar')->with('success-update', 'Usuario actualizado con éxito');
    }


    public function destroy($user_id)
    {

        $user = User::with('persona')->findOrFail($user_id);

        // Eliminar foto si existe
        if ($user->persona->photo) {
            Storage::disk('public')->delete($user->persona->photo);
        }

        // Eliminar primero los registros relacionados (si existen)
        optional($user->docente)->delete();
        optional($user->secretaria)->delete();
        optional($user->tutor)->delete();

        // Eliminar persona y usuario
        $user->persona->delete();
        $user->delete();

        return redirect()->route('users')->with('success-destroy', 'Usuario eliminado exitosamente.');
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
            'email' => 'required|email|unique:users,email,'.$user->user_id.',user_id',
            'photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
        ]);

        $personaData = $request->only([
            'name',
            'lastname',
            'dni',
            'phone',
            'address',
        ]);

        if ($request->hasFile('photo')) {
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
