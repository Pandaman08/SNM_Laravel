<?php

use App\Http\Controllers\SecretariaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TutorController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\SeccionController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\MatriculaController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\AnioEscolarController;
use App\Http\Controllers\TipoCalificacionController;
use App\Http\Controllers\PagoController;


Route::get('/', [UserController::class, 'index'])->name('login.index');
Route::post('/', [UserController::class, 'login'])->name('login');
Route::get('/home', [AdminController::class, 'index_admin'])->name('home.admin');
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
//-------------------------Matriculas-----------------------------------

// Rutas principales
Route::get('/matriculas', [MatriculaController::class, 'index'])->name('matriculas.index');
Route::get('/matriculas/crear', [MatriculaController::class, 'create'])->name('matriculas.create');
Route::post('/matriculas', [MatriculaController::class, 'store'])->name('matriculas.store');
Route::get('/matriculas/{codigo_matricula}', [MatriculaController::class, 'show'])->name('matriculas.show');

Route::get('/matriculas/{matricula}/ficha', [MatriculaController::class, 'generarFicha'])
     ->name('matriculas.ficha');
     
// Rutas para tutores
Route::get('/mis-matriculas', [MatriculaController::class, 'misMatriculas'])->name('matriculas.mis-matriculas');
Route::get('/solicitar-matricula', [MatriculaController::class, 'createTutor'])->name('matriculas.create-tutor');
Route::post('/solicitar-matricula', [MatriculaController::class, 'storeTutor'])->name('matriculas.store-tutor');

// Rutas para aprobar/rechazar (admin/secretaria)
Route::patch('/matriculas/{codigo_matricula}/aprobar', [MatriculaController::class, 'aprobar'])->name('matriculas.aprobar');
Route::patch('/matriculas/{codigo_matricula}/rechazar', [MatriculaController::class, 'rechazar'])->name('matriculas.rechazar');

// Rutas AJAX
Route::get('/obtener-grados', [MatriculaController::class, 'obtenerGrados'])->name('matriculas.obtener-grados');
Route::get('/obtener-secciones', [MatriculaController::class, 'obtenerSecciones'])->name('matriculas.obtener-secciones');
Route::get('/buscar-estudiante', [MatriculaController::class, 'buscarEstudiante'])->name('matriculas.buscar-estudiante');


//------------------------ estudiantes ---------------------------------
Route::get('estudiantes',[EstudianteController::class,'index'])->name('estudiantes.index');
Route::get('/estudiantes/buscar',[AdminController::class,'showEstudiante'])->name('estudiantes.buscar');

//------------------------ docentes ---------------------------------
//Route::get('/docentes',[DocenteController::class, 'index'])->name('docente');
//Route::get('/docentes/create',[DocenteController::class, 'create'])->name('docente.create');
Route::get('/docentes/buscar', [AdminController::class, 'showDocente'])->name('docentes.buscar');

// ------------------------ tesoreros -------------------------
Route::get('/tesoreros/buscar', [SecretariaController::class, 'showTesoreros'])->name('tesoreros.buscar');


// ---------- periodos -----------

Route::resource('periodos', PeriodoController::class);
Route::resource('anios-escolares', AnioEscolarController::class);

// ---------- Competencia ---------------------------------------------------

Route::resource('tipos-calificacion', TipoCalificacionController::class)->except(['show']);

Route::resource('pagos', PagoController::class)->except(['create']);

Route::get('/pagos/create/{matricula}', [PagoController::class, 'create'])->name('pagos.create');