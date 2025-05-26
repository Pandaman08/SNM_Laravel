<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
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
            // return redirect()->route('dashboard')->with('success', 'Sesión iniciada correctamente')
            // $user = Auth::user();
            
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
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('lastname', 'like', '%' . $query . '%')
                  ->orWhere('email', 'like', '%' . $query . '%');
            });
        })
        ->paginate(10);
        

         return view('pages.admin.show', compact('users'));
}

public function store(Request $request)
{
    
    $request->validate([
        'name' => 'required|string|max:100',
        'lastname' => 'required|string|max:100',
        'phone' => 'required|string|max:15',
        'address' => 'required|string|max:200',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|regex:/[A-Z]/|regex:/[a-z]/',
        'photo' => 'nullable|image|max:4096|mimes:jpg,png,jpeg',
    ]);

   
    $rutaImagen = null;
    if ($request->hasFile('photo')) {
        $photo = $request->file('photo');
        $rutaImagen = $photo->store('photos', 'public'); 
    }

    
    $user = User::create([
        'name' => $request->input('firstname'),
        'lastname' => $request->input('lastname'),
        'phone' => $request->input('phone'),
        'address' => $request->input('address'),
        'email' => $request->input('email'),
        'password' => Hash::make($request->input('password')),
        'photo' => $rutaImagen,
    ]);

    $user->is_approved = true;
    $user->save();

   
    return redirect()->route('users')->with('success', 'Usuario creado exitosamente.');
}

public function edit($id)
{

    $users = User::findOrFail($id);
    return view('pages.admin.show', compact('users'));
}

public function update(Request $request, $user_id)
{
    $user = User::findOrFail($user_id);

    $request->validate([
        'name' => 'required|string|max:100',
        'lastname' => 'required|string|max:100',
        'email' => 'required|email|unique:users,email,' . $user->user_id,
        'phone' => 'required|string|max:15',
        'address' => 'required|string|max:200',
        'password' => 'nullable|min:6|same:password_confirmation',
        'photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);


    if ($request->hasFile('photo')) {
        $path = $request->file('photo')->store('photos', 'public');
        $user->photo = $path;
    }

    $user->update([
        'name' => $request->firstname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'phone' => $request->phone,
        'address' => $request->address,
    ]);


    return redirect()->route('users')->with('success-update', 'Usuario actualizada con éxito');

}


public function destroy($user_id)
{
 
    $user = User::findOrFail($user_id);

    if ($user->photo && Storage::disk('public')->exists($user->photo)) {
        Storage::disk('public')->delete($user->photo);
    }

    $user->delete();

    return redirect()->route('users')->with('success-destroy', 'Usuario eliminado exitosamente.');
}


}
