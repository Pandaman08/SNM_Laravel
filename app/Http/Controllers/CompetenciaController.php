<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Competencia;

class CompetenciaController extends Controller
{
    public function index(Request $request) {
        $user = Auth::user(); 
        $competencia = Competencia::paginate(5);
        return view('pages.admin.competencias.index', compact('user','competencia'));
    }

    public function create() { 
        return view('pages.admin.competencias.create'); 
    }
}
