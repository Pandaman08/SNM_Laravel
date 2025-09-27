<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\User;
use App\Models\Tutor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class TutorController extends Controller
{
    private const STRING_REQUIRED_MAX_255 = 'required|string|max:255';

        public function index_tutor()
    {
        $users = User::where('estado', false)->paginate(10);
        return view("pages.admin.users.tutores", compact("users"));
    }

    
    public function create(){
        return view('pages.admin.tutor.register');
    }

      public function panel_tutor()
    {
        $user = Auth::user();
        return view("pages.admin.panels.tutor", compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => self::STRING_REQUIRED_MAX_255,
            'apellidos' => self::STRING_REQUIRED_MAX_255,
            'dni' => 'required|string|size:8|unique:personas,dni',
            'sexo' => 'required|in:masculino,femenino',
            'estado_civil' => 'required|in:soltero,casado,divorciado,viudo',
            'celular' => 'required|string|max:15',
            'correo' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'fecha_nacimiento' => 'required|date',
            'direccion' => self::STRING_REQUIRED_MAX_255,
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $sexoMap = [
        'masculino' => 'M',
        'femenino' => 'F'
        ];
        
        $estadoCivilMap = [
            'soltero' => 'S',
            'casado' => 'C',
            'divorciado' => 'D',
            'viudo' => 'V'
        ];

        try {
            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $fotoPath = $request->file('foto')->store('tutores', 'public');
            }

            $persona = Persona::create([
                'name' => $request->nombre,
                'lastname' => $request->apellidos,
                'dni' => $request->dni,
                'phone' => $request->celular,
                'sexo' => $sexoMap[$request->sexo],
                'estado_civil' => $estadoCivilMap[$request->estado_civil],
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'address' => $request->direccion,
                'photo' => $fotoPath
            ]);

            $user = User::create([
                'name' => $request->nombre . ' ' . $request->apellidos,
                'email' => $request->correo,
                'password' => Hash::make($request->password),
                'rol' => 'tutor',
                'persona_id' => $persona->persona_id,
                'estado' => false
            ]);

            Tutor::create([
                'user_id' => $user->user_id,
                'parentesco' => 'tutor'
            ]);

            return redirect()->route('tutor.register')->with('success', 'Tutor registrado exitosamente');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al registrar el tutor: ' . $e->getMessage());
        }
    }
}
