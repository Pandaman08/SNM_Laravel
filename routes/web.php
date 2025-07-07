<?php

use App\Http\Controllers\ReporteNotasController;
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
use App\Http\Controllers\CompetenciaController;


Route::get('/', [UserController::class, 'index'])->name('login.index');
Route::post('/', [UserController::class, 'login'])->name('login');
Route::get('/panel/admin', [AdminController::class, 'panel_admin'])->name('home.admin');
Route::get('/panel/docente', [AdminController::class, 'panel_docente'])->name('home.docente');
Route::get('/panel/tesorero', [AdminController::class, 'panel_secretaria'])->name('home.secretaria');
Route::get('/panel/tutor', [AdminController::class, 'panel_tutor'])->name('home.tutor');


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

//------------------------Grado--------------------------------------
Route::resource('grados', GradoController::class);

Route::resource('secciones', SeccionController::class);

//------------------------Asignatura--------------------------------------
// Route::resource('asignaturas', AsignaturaController::class);
Route::get('/asignaturas', [AsignaturaController::class, 'index'])->name('asignaturas.index');
Route::get('/asignaturas/create', [AsignaturaController::class, 'create'])->name('asignaturas.create');
Route::post('/asignaturas', [AsignaturaController::class, 'store'])->name('asignaturas.store');
Route::get('/asignaturas/{id}/edit', [AsignaturaController::class, 'edit'])->name('asignaturas.edit');
Route::put('/asignaturas/{id}', [AsignaturaController::class, 'update'])->name('asignaturas.update');
Route::delete('/asignaturas/{id}', [AsignaturaController::class, 'destroy'])->name('asignaturas.destroy');
Route::get('/asignaturas/asignar-docentes',[AsignaturaController::class, 'show'])->name('asignaturas.asignar.docentes');
Route::get('/asignaturas/asignar/{id}', [AsignaturaController::class, 'asignar'])->name('asignaturas.asignar');
Route::post('/asignaturas/asignar', [AsignaturaController::class, 'storeAsignacion'])->name('asignaturas.storeAsignacion');
Route::get('cancelar', function () { 
     return redirect()->route('asignaturas.asignar.docentes'); 
})->name('ruta.cancelar'); 
Route::get('/asignaturas-asignadas', [DocenteController::class, 'index_asignaturas'])->name('docentes.asignaturas');
Route::get('/estudiantes-matriculado/{id_asignatura}/asignatura', [DocenteController::class, 'index_estudiantes'])->name('docentes.estudiantes');
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
Route::get('/obtener-estudiante', [MatriculaController::class, 'obtenerEstudiante'])->name('matriculas.obtener-estudiante');
Route::get('/obtener-secciones', [MatriculaController::class, 'obtenerSecciones'])->name('matriculas.obtener-secciones');
Route::get('/buscar-estudiante', [MatriculaController::class, 'buscarEstudiante'])->name('matriculas.buscar-estudiante');


//------------------------ estudiantes ---------------------------------
Route::get('estudiantes',[EstudianteController::class,'index'])->name('estudiantes.index');
Route::get('/estudiantes/buscar',[EstudianteController::class,'showEstudiante'])->name('estudiantes.buscar');
Route::put('/estudiantes/{estudiante_id}', [EstudianteController::class, 'update'])->name('estudiantes.update');
//------------------------ docentes ---------------------------------
//Route::get('/docentes',[DocenteController::class, 'index'])->name('docente');
//Route::get('/docentes/create',[DocenteController::class, 'create'])->name('docente.create');
Route::get('/docentes/buscar', [DocenteController::class, 'index'])->name('docentes.buscar');
Route::post('/docentes', [DocenteController::class, 'store'])->name('docentes.store');
Route::put('/docentes/{user_id}', [DocenteController::class, 'update'])->name('docentes.update');
Route::get('/docentes/{user_id}/edit', [DocenteController::class, 'edit'])->name('docentes.edit');
Route::delete('/docentes/{user_id}/delete', [DocenteController::class, 'destroy'])->name('docentes.destroy');

Route::get('docente/mis-estudiantes', [DocenteController::class, 'misEstudiantes'])->name('docente.mis_estudiantes');
Route::get('docente/ver-estudiantes/{grado_id}', [DocenteController::class, 'verEstudiantesPorGrado'])
    ->name('docente.ver_estudiantes');


// ------------------------ tesoreros -------------------------
Route::get('/secretarias/buscar', [SecretariaController::class, 'showTesoreros'])->name('secretarias.buscar');
Route::post('/secretarias', [SecretariaController::class, 'store'])->name('secretarias.store');
Route::put('/secretarias/{user_id}', [SecretariaController::class, 'update'])->name('secretarias.update');
Route::get('/secretarias/{user_id}/edit', [SecretariaController::class, 'edit'])->name('secretarias.edit');
Route::delete('/secretarias/{user_id}/delete', [SecretariaController::class, 'destroy'])->name('secretarias.destroy');

// ---------- periodos -----------

Route::resource('periodos', PeriodoController::class)->except(['destroy']);
Route::delete('/periodos/{periodo_id}/delete', [UserController::class, 'destroy'])->name('periodos.destroy');
Route::resource('anios-escolares', AnioEscolarController::class);

// ---------- Competencia ---------------------------------------------------
Route::resource('competencias', CompetenciaController::class);

Route::resource('tipos-calificacion', TipoCalificacionController::class)->except(['show']);
Route::resource('reporte_notas', ReporteNotasController::class)->except(['show']);
// Route::get('/reporte_notas/{reporteId}/update', [ReporteNotasController::class, 'update'])->name('reporte_notas.update');
// Route::get('/reporte_notas/create/{codigo_matricula}/estudiante', [ReporteNotasController::class, 'create'])->name('reporte_notas.create');
// Route::get('/reporte_notas/{codigo_matricula}/{id_asignatura}/create', [ReporteNotasController::class, 'create'])->name('reporte_notas.create');
Route::get('/reporte_notas/{codigo_matricula}/{id_asignatura}/show', [ReporteNotasController::class, 'estudiante_calificaciones'])->name('reporte_notas.show');
Route::get('/reporte_notas/docente/{id_asignatura}', [ReporteNotasController::class, 'docente_view'])->name('reporte_notas.docente');
Route::get('/reporte_notas/tutor/estudiantes', [ReporteNotasController::class, 'index_estudiantes_tutor'])->name('reporte_notas.tutor');
Route::get('/reporte_notas/tutor/estudiantes/{id_asignatura}', [ReporteNotasController::class, 'verNotasEstudiante'])->name('reporte_notas.tutor.estudiante');
Route::get('/reporte-notas/pdf/{codigo_matricula}', [ReporteNotasController::class, 'generarReportePdf'])->name('reporte.notas.pdf');
Route::resource('pagos', PagoController::class)->except(['create']);

Route::get('/pagos/create/{matricula_id}/matricula', [PagoController::class, 'create'])->name('pagos.create');


//----------Asistencia-----------------------
Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
Route::get('/asistencias/create', [AsistenciaController::class, 'create'])->name('asistencias.create');
Route::post('/asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
Route::get('/asistencias/{codigo_estudiante}', [AsistenciaController::class, 'show'])->name('asistencias.show');
Route::get('/asistencias/{codigo_estudiante}/edit', [AsistenciaController::class, 'edit'])->name('asistencias.edit');
Route::put('/asistencias/{codigo_estudiante}', [AsistenciaController::class, 'update'])->name('asistencias.update');
Route::delete('/asistencias/{id_asistencia}', [AsistenciaController::class, 'destroy'])->name('asistencias.destroy');


