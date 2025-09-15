<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\AsignaturaDocente;
use App\Enums\UserRole;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Storage;
use App\Models\Matricula;
use App\Mail\TutorStatusNotification;

class AdminController extends Controller
{
    public function panel_admin()
    {
        $user = Auth::user();
        $matriculas = Matricula::get();
        return view("pages.admin.panels.admin", compact('user', 'matriculas'));
    }

    public function panel_docente()
    {
        $user = Auth::user();
        $numAsign= AsignaturaDocente::where('codigo_docente','=',$user->docente->codigo_docente)->get()->count();
        return view("pages.admin.panels.docente", compact('user','numAsign'));
    }

    public function panel_secretaria()
    {
        $user = Auth::user();
        $matriculas = Matricula::get();
        return view("pages.admin.panels.secretaria", compact('user', 'matriculas'));
    }
    
    public function panel_tutor()
    {
        $user = Auth::user();
        return view("pages.admin.panels.tutor", compact('user'));
    }
    
    public function index_tutor()
    {
        $users = User::where('estado', false)->paginate(10);
        return view("pages.admin.users.tutores", compact("users"));
    }

    public function showDocente(Request $request)
    {
        $query = $request->input('search');

        $users = User::where('rol', '=', 'docente')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('lastname', 'like', '%' . $query . '%')
                        ->orWhere('email', 'like', '%' . $query . '%');
                });
            })
            ->paginate(10);
        $roles = UserRole::cases();

        return view('pages.admin.docentes.index', compact('users', 'roles'));
    }

    // MÉTODO MODIFICADO: Aprobar usuario con notificación email
    public function approveUser($id)
    {
        $user = User::with('persona')->findOrFail($id);
        $user->estado = true;
        $user->save();

        // Enviar email de aprobación
        try {
            Mail::to($user->email)->send(new TutorStatusNotification($user, 'approved'));
        } catch (\Exception $e) {
            \Log::error('Error enviando email de aprobación: ' . $e->getMessage());
        }

        return redirect()->route('tutores.panel-aprobar')->with('success-approve', 'Usuario aprobado correctamente y notificación enviada.');
    }

    // MÉTODO NUEVO: Rechazar usuario con motivo y notificación email
    public function rejectUser(Request $request, $id)
    {
        $request->validate([
            'reason' => 'required|string|max:500'
        ], [
            'reason.required' => 'El motivo del rechazo es obligatorio',
            'reason.max' => 'El motivo no puede exceder 500 caracteres'
        ]);

        $user = User::with('persona')->findOrFail($id);

        // Enviar email de rechazo antes de eliminar
        try {
            Mail::to($user->email)->send(new TutorStatusNotification($user, 'rejected', $request->reason));
        } catch (\Exception $e) {
            \Log::error('Error enviando email de rechazo: ' . $e->getMessage());
        }

        // Eliminar archivos y usuario
        if ($user->persona->photo && Storage::disk('public')->exists($user->persona->photo)) {
            Storage::disk('public')->delete($user->persona->photo);
        }

        $user->persona->delete();
        $user->tutor->delete();
        $user->delete();

        return redirect()->route('tutores.panel-aprobar')->with('success-destroy', 'Solicitud rechazada, notificación enviada y usuario eliminado.');
    }

    // MÉTODO MODIFICADO: Solo eliminar usuarios ya aprobados (sin notificación)
    public function destroy_person($id)
    {
        $user = User::findOrFail($id);

        if (!$user->estado) {
            return back()->withErrors(['error' => 'Use la opción de rechazar para usuarios no aprobados.']);
        }

        if ($user->persona->photo && Storage::disk('public')->exists($user->persona->photo)) {
            Storage::disk('public')->delete($user->persona->photo);
        }

        $user->persona->delete();
        $user->tutor->delete();
        $user->delete();

        return redirect()->route('tutores.panel-aprobar')->with('success-destroy', 'Usuario eliminado exitosamente.');
    }
}
