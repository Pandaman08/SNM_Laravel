<?php

use Illuminate\Support\Facades\Route;
use app\Http\Controllers\UseController;
use app\Http\Controllers\AdminController;
use app\Http\Controllers{
    TutorController,
    EstudianteController,
    AsistenciaController
};

Route::get('/', [UserController::class, 'index'])->name('login.index');
Route::post('/', [UserController::class, 'login'])->name('login');
Route::get('/home', [AdminController::class, 'index'])->name('home');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// ----------------------- users ---------------------------------
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/buscar', [UserController::class, 'showUser'])->name('users.buscar');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::delete('/users/{id}/delete', [UserController::class, 'destroy'])->name('users.destroy');

// ------------------------user perfil -------------

Route::get('/users/me', [UserController::class, 'edit_user'])->name('users.edit_user');
Route::put('/users/me/{id}', [UserController::class, 'update_user'])->name('users.update_user');
Route::put('/users/{id}/photo', [UserController::class, 'update_photo'])->name('users.update_photo');
Route::put('users/{id}/password', [UserController::class, 'update_password'])->name('users.update_password');

//------------------------tutores--------------------------------------
Route::get('tutores', [TutorController::class, 'indexTutores'])->name('tutores.index');

//------------------------ estudiantes ---------------------------------
Route::get('estudiantes',[EstudianteController::class,'index'])->name('estudiantes.index');
