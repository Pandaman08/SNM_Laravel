<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pago;
use App\Models\Persona;
use App\Models\Matricula;
use Illuminate\Support\Facades\Auth;
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

       public function panel_secretaria()
    {
        $user = Auth::user();
        $matriculas = Matricula::get();
        $pagos = Pago::get();
        $pagosReciente = Pago::orderBy('created_at', 'desc')->take(4)->get();
        $pagosPendientes = Pago::where('estado', 'Pendiente')->get();
        return view("pages.admin.panels.secretaria", compact('user', 'matriculas','pagos','pagosReciente','pagosPendientes'));
    }

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

            if ($user->secretaria) {
                $user->secretaria->delete();
            }
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
