<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Enums\UserRole;

use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index(){
        $user = Auth::user(); 
        return view("pages.admin.index", compact('user'));
    } 

    public function index_tutor(){

        $users = User::where('estado', false)->paginate(10);

        return view("pages.admin.users.tutores", compact("users"));
    }

        public function showDocente(Request $request)
    {
        $query = $request->input('search');

        $users = User::where('rol','=','docente')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->where('name', 'like', '%' . $query . '%')
                        ->orWhere('lastname', 'like', '%' . $query . '%')
                        ->orWhere('email', 'like', '%' . $query . '%');
                });
            })
            ->paginate(10);
           $roles= UserRole::cases() ;

        return view('pages.admin.docentes.index', compact('users','roles'));
    }


    public function approveUser($id)
{
    $user = User::findOrFail($id);
    $user->estado = true;
    $user->save();

    return redirect()->route('tutores.panel-aprobar')->with('success-approve', 'Usuario aprobado correctamente.');
}
public function destroy_person($id)
{
    $user = User::findOrFail($id);

    
    if ($user->estado) {
        return back()->withErrors(['error' => 'No se puede eliminar un usuario aprobado.']);
    }

    if ($user->person->photo && Storage::disk('public')->exists($user->person->photo)) {
        Storage::disk('public')->delete($user->person->photo);
    }

    $user->delete();

    return redirect()->route('tutores.panel-aprobar')->with('success-destroy', 'Usuario eliminado exitosamente.');
}
}
