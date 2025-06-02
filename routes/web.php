<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;


use App\Http\Controllers\GradoController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\AsignaturaController;



use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\AnioEscolarController;
use App\Http\Controllers\TipoCalificacionController;

Route::get('/', [UserController::class, 'index'])->name('login.index');
Route::post('/', [UserController::class, 'login'])->name('login');
Route::get('/home', [AdminController::class, 'index'])->name('home');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// ----------------------- users ---------------------------------
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/buscar', [UserController::class, 'showUser'])->name('users.buscar');
Route::put('/users/{user_id}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/{user_id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::delete('/users/{user_id}/delete', [UserController::class, 'destroy'])->name('users.destroy');

// ------------------------user perfil -------------

Route::get('/users/me', [UserController::class, 'edit_user'])->name('users.edit_user');
Route::put('/users/me/{id}', [UserController::class, 'update_user'])->name('users.update_user');
Route::put('/users/{id}/photo', [UserController::class, 'update_photo'])->name('users.update_photo');
Route::put('users/{id}/password', [UserController::class, 'update_password'])->name('users.update_password');


Route::resource('grados', GradoController::class);
Route::resource('secciones', SeccionController::class);
Route::resource('asignaturas', AsignaturaController::class);

//------------------------tutores--------------------------------------
// Route::get('tutores', [TutorController::class, 'indexTutores'])->name('tutores.index');
Route::get('/tutores/aprobar', [AdminController::class, 'index_tutor'])->name('tutores.panel-aprobar');
Route::post('/tutores/{id}/approve', [AdminController::class, 'approveUser'])->name('person.approve');
  Route::delete('tutores/tutor/{id}', [AdminController::class, 'destroy_person'])->name('person.destroy_person');
Route::get('/tutor/register', [TutorController::class, 'create'])->name('tutor.register');
Route::post('/tutor/register', [TutorController::class, 'store'])->name('tutor.store');

//------------------------ estudiantes ---------------------------------
Route::get('estudiantes',[EstudianteController::class,'index'])->name('estudiantes.index');

// ------------------- docentes----------------
Route::get('/docentes/buscar', [AdminController::class, 'showDocente'])->name('docentes.buscar');

// ---------- periodos -----------

Route::resource('periodos', PeriodoController::class);

Route::resource('anios-escolares', AnioEscolarController::class);

Route::resource('tipos-calificacion', TipoCalificacionController::class)->except(['show']);