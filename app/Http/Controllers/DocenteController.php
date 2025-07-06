<?php

namespace App\Http\Controllers;
use App\Models\Asignatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Persona;
use App\Models\Docente;
use App\Models\AsignaturaDocente;
use App\Models\ReporteNota;
use App\Models\TipoCalificacion;
use App\Models\Matricula;
use App\Enums\UserRole;
use App\Models\Periodo;
use App\Models\Grado;
use App\Models\Seccion;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('search');

        $users = User::with(['persona', 'docente'])
            ->where('rol', 'docente')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->whereHas('persona', function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('lastname', 'like', '%' . $query . '%');
                })->orWhere('email', 'like', '%' . $query . '%');
            })
            ->paginate(10);

        return view('pages.admin.docentes.index', compact('users'));
    }

    public function index_asignaturas()
    {
        $user = Auth::user();
        $detalles = AsignaturaDocente::where('codigo_docente', $user->docente->codigo_docente)->get();


        return view('pages.admin.docentes.asignaturas', compact('detalles'));
    }

    public function index_estudiantes($id_asignatura)
    {
        $asignatura = Asignatura::where('codigo_asignatura', $id_asignatura)->firstOrFail();

        $grado = $asignatura->grado;

        // Obtener todas las secciones de ese grado
        $secciones = $grado->secciones; // asegúrate de tener esta relación en el modelo Grado

        // Obtener todas las matrículas de todas las secciones del grado
        $matriculas = collect();
        foreach ($secciones as $seccion) {
            $matriculas = $matriculas->merge($seccion->matriculas);
        }

        return view('pages.admin.docentes.estudiantes', compact('matriculas', 'asignatura'));
    }

    public function asignar_nota($codigo_matricula)
    {
        $matricula = Matricula::with('detalleAsignatura.competencia')->findOrFail($codigo_matricula);
        $tipos_cal = TipoCalificacion::all();
        $periodos = Periodo::all();

        $detalles_asignatura = $matricula->detalleAsignatura;

        return view('pages.admin.reporte_notas.create', compact('matricula', 'detalles_asignatura', 'tipos_cal', 'periodos'));
    }



    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'lastname' => 'required|string|max:100',
                'dni' => 'required|string|size:8',
                'phone' => 'required|string|max:15',
                'sexo' => 'required|in:M,F',
                'address' => 'required|string|max:200',
                'fecha_nacimiento' => 'required|date',
                'especialidad' => 'required|string|max:100',
                'jornada_laboral' => 'required|numeric',
                'departamento_estudios' => 'required|string|max:100',
                'fecha_contratacion' => 'required|date',
                'photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
            ]);

            // Generar email automático
            $nombre = strtolower($request->name);
            $apellido = strtolower($request->lastname);
            $randomNumbers = rand(1000, 9999);
            $email = $nombre . substr($apellido, 0, 1) . '_' . $randomNumbers . '@bruning.com';

            // Crear Persona
            $persona = Persona::create([
                'name' => $request->name,
                'lastname' => $request->lastname,
                'dni' => $request->dni,
                'phone' => $request->phone,
                'sexo' => $request->sexo,
                'address' => $request->address,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'photo' => $request->hasFile('photo')
                    ? $request->file('photo')->store('profile_photos', 'public')
                    : null
            ]);

            // Crear User
            $user = User::create([
                'persona_id' => $persona->persona_id,
                'email' => $email,
                'password' => Hash::make('password'),
                'rol' => 'docente',
                'estado' => true
            ]);

            // Crear Docente
            $docente = Docente::create([
                'user_id' => $user->user_id,
                'especialidad' => $request->especialidad,
                'jornada_laboral' => $request->jornada_laboral,
                'fecha_contratacion' => $request->fecha_contratacion,
                'departamento_estudios' => $request->departamento_estudios
            ]);

            DB::commit();

            return redirect()->route('docentes.buscar')->with('success', 'Docente creado exitosamente');

        } catch (\Exception $e) {
            Log::error('Error al crear docente: ' . $e->getMessage());
            return back()->with('error', 'Error al crear docente: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $user_id)
    {
        DB::beginTransaction();

        try {
            $user = User::with(['persona', 'docente'])->findOrFail($user_id);

            $request->validate([
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

            // Actualizar Persona
            $personaData = [
                'name' => $request->edit_name,
                'lastname' => $request->edit_lastname,
                'dni' => $request->edit_dni,
                'phone' => $request->edit_phone,
                'sexo' => $request->edit_sexo,
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

            // Actualizar Docente
            $user->docente->update([
                'especialidad' => $request->edit_especialidad,
                'jornada_laboral' => $request->edit_jornada_laboral,
                'fecha_contratacion' => $request->edit_fecha_contratacion,
                'departamento_estudios' => $request->edit_departamento_estudios
            ]);

            DB::commit();

            return redirect()->route('docentes.buscar')->with('success-update', 'Docente actualizado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar docente: ' . $e->getMessage());
            return back()->with('error', 'Error al actualizar docente: ' . $e->getMessage());
        }
    }

    public function destroy($user_id)
    {
        DB::beginTransaction();

        try {
            $user = User::with(['persona', 'docente'])->findOrFail($user_id);

            // Eliminar foto si existe
            if ($user->persona->photo) {
                Storage::disk('public')->delete($user->persona->photo);
            }

            // Eliminar primero el docente
            if ($user->docente) {
                $user->docente->delete();
            }

            // Luego el usuario
            $user->delete();

            // Finalmente la persona
            $user->persona->delete();

            DB::commit();

            return redirect()->route('docentes.buscar')->with('success-destroy', 'Docente eliminado exitosamente');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar docente: ' . $e->getMessage());
            return back()->with('error', 'No se pudo eliminar el docente: ' . $e->getMessage());
        }
    }


public function misEstudiantes()
{
    $user = Auth::user();

    $asignaciones = AsignaturaDocente::with('asignatura.grado')
        ->where('codigo_docente', $user->docente->codigo_docente)
        ->get()
        ->filter(function ($item) {
            return optional($item->asignatura)->id_grado;
        })
        ->unique(fn($item) => $item->asignatura->id_grado)
        ->values();

    return view('pages.admin.docentes.mis-estudiantes', compact('asignaciones'));
}

public function verEstudiantesPorGrado(Request $request, $grado_id)
{
    $seccionId = $request->input('seccion_id');

    // Obtener las secciones del grado
    $secciones = Seccion::where('id_grado', $grado_id)->get();
    $seccionIds = $secciones->pluck('id_seccion')->toArray();

    // Consultar las matrículas usando seccion_id
    $matriculas = Matricula::with(['estudiante.persona'])
        ->whereIn('seccion_id', $seccionIds)
        ->when($seccionId, function ($query) use ($seccionId) {
            $query->where('seccion_id', $seccionId);
        })
        ->get();

    $grado = Grado::find($grado_id);
    $gradoNombre = $grado?->nombre_completo ?? 'Sin grado';

    return view('pages.admin.docentes.estudiantes-por-grado', compact('matriculas', 'gradoNombre', 'secciones'));
}


}