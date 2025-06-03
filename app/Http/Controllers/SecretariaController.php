<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Enums\UserRole;
class SecretariaController extends Controller
{


    public function showTesoreros(Request $request)
    {
        $query = $request->input('search');

        $users = User::where('rol','=','secretaria')
            ->when($query, function ($queryBuilder) use ($query) {
                $queryBuilder->where(function ($q) use ($query) {
                    $q->Where('email', 'like', '%' . $query . '%');
                });
            })
            ->paginate(10);
        $roles = UserRole::cases();

        return view('pages.admin.tesoreras.index', compact('users', 'roles'));
    }
}
