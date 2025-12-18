<?php

use App\Http\Controllers\ReporteNotasController;
use App\Http\Controllers\SecretariaController;
use App\Http\Controllers\AuxiliarController;
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
use App\Http\Controllers\AsistenciaQRController;
use App\Http\Controllers\ReporteController;

Route::get('/', [UserController::class, 'index'])->name('login.index');
Route::post('/', [UserController::class, 'login'])->name('login');
Route::get('/tutor/register', [TutorController::class, 'create'])->name('tutor.register');
Route::post('/tutor/register', [TutorController::class, 'store'])->name('tutor.store');

Route::middleware('auth')->group(function () {
    Route::get('/panel/admin', [AdminController::class, 'panel_admin'])->name('home.admin');
    Route::get('/panel/docente', [DocenteController::class, 'panel_docente'])->name('home.docente');
    Route::get('/panel/tesorero', [SecretariaController::class, 'panel_secretaria'])->name('home.secretaria');
    Route::get('/panel/tutor', [TutorController::class, 'panel_tutor'])->name('home.tutor');
    Route::get('/panel/auxiliar', [AuxiliarController::class, 'panel_auxiliar'])->name('home.auxiliar');

    Route::get('/logout', [UserController::class, 'logout'])->name('logout');

    // ----------------------- RUTAS DE AUXILIAR ---------------------------------
    Route::prefix('auxiliar')->group(function () {
        Route::get('/scanner', [AuxiliarController::class, 'scanner'])->name('asistencia.scanner');
        Route::post('/process-scan', [AuxiliarController::class, 'processScan'])->name('asistencia.process-scan');
        Route::post('/auxiliar/marcar-ausencias-manual', [AuxiliarController::class, 'marcarAusenciasManual'])->name('auxiliar.marcar-ausencias');
        
        Route::get('/justificaciones', [AuxiliarController::class, 'justificacionesPendientes'])->name('auxiliar.justificaciones');
        Route::post('/aprobar-justificacion/{id}', [AuxiliarController::class, 'aprobarJustificacion'])->name('auxiliar.aprobar-justificacion');
        Route::post('/rechazar-justificacion/{id}', [AuxiliarController::class, 'rechazarJustificacion'])->name('auxiliar.rechazar-justificacion');
    });

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
    Route::get('/asignaturas', [AsignaturaController::class, 'index'])->name('asignaturas.index');
    Route::get('/asignaturas/create', [AsignaturaController::class, 'create'])->name('asignaturas.create');
    Route::post('/asignaturas', [AsignaturaController::class, 'store'])->name('asignaturas.store');
    Route::get('/asignaturas/{id}/edit', [AsignaturaController::class, 'edit'])->name('asignaturas.edit');
    Route::put('/asignaturas/{id}', [AsignaturaController::class, 'update'])->name('asignaturas.update');
    Route::delete('/asignaturas/{id}', [AsignaturaController::class, 'destroy'])->name('asignaturas.destroy');
    Route::get('/asignaturas/asignar-docentes', [AsignaturaController::class, 'show'])->name('asignaturas.asignar.docentes');
    Route::get('/asignaturas/asignar/{id}', [AsignaturaController::class, 'asignar'])->name('asignaturas.asignar');
    Route::post('/asignaturas/store-asignacion', [AsignaturaController::class, 'storeAsignacion'])->name('asignaturas.storeAsignacion');
    Route::put('/actualizar-asignacion', [AsignaturaController::class, 'updateAsignacion'])->name('asignaturas.updateAsignacion');
    Route::delete('/remover-asignacion', [AsignaturaController::class, 'removeAsignacion'])->name('asignaturas.removeAsignacion');
    Route::post('/asignaturas/agregar-docente', [AsignaturaController::class, 'agregarDocente'])->name('asignaturas.agregarDocente');
    Route::post('/asignaturas/remover-docente', [AsignaturaController::class, 'removeAsignacion'])->name('asignaturas.removeAsignacion');
    Route::get('cancelar', function () {
        return redirect()->route('asignaturas.asignar.docentes');
    })->name('ruta.cancelar');
    Route::get('/asignaturas/{codigoAsignatura}/docentes-activos', [AsignaturaController::class, 'getDocentesActivos'])->name('asignaturas.docentesActivos');
    Route::get('/asignaturas/{codigoAsignatura}/seccion/{idSeccion}/docentes-activos', [AsignaturaController::class, 'getDocentesActivosEnSeccion'])->name('asignaturas.docentesActivosEnSeccion');
    Route::get('/asignaturas-asignadas', [DocenteController::class, 'index_asignaturas'])->name('docentes.asignaturas');
    Route::get('/estudiantes-matriculado/{id_asignatura}/asignatura', [DocenteController::class, 'index_estudiantes'])->name('docentes.estudiantes');

    //------------------------tutores--------------------------------------
    Route::get('/tutores/aprobar', [TutorController::class, 'index_tutor'])->name('tutores.panel-aprobar');
    Route::post('/tutores/{id}/approve', [AdminController::class, 'approveUser'])->name('person.approve');
    Route::post('/tutores/{id}/reject', [AdminController::class, 'rejectUser'])->name('person.reject');
    Route::delete('tutores/tutor/{id}', [AdminController::class, 'destroy_person'])->name('person.destroy_person');

    //-------------------------Matriculas-----------------------------------
    Route::get('/matriculas', [MatriculaController::class, 'index'])->name('matriculas.index');
    Route::get('/matriculas/crear', [MatriculaController::class, 'create'])->name('matriculas.create');
    Route::post('/matriculas', [MatriculaController::class, 'store'])->name('matriculas.store');
    Route::get('/reportes/matriculados', [ReporteController::class, 'matriculados'])->name('matriculas.reporte');
    Route::get('/matriculas/{matricula}/ficha', [MatriculaController::class, 'generarFicha'])->name('matriculas.ficha');
    Route::get('/matriculas/{codigo_matricula}/editar', [MatriculaController::class, 'edit'])->name('matriculas.editar');
    Route::put('/matriculas/{codigo_matricula}', [MatriculaController::class, 'update'])->name('matriculas.update');
    Route::get('/matriculas/{codigo_matricula}', [MatriculaController::class, 'show'])->name('matriculas.show');
    Route::get('/mis-matriculas', [MatriculaController::class, 'misMatriculas'])->name('matriculas.mis-matriculas');
    Route::get('/solicitar-matricula', [MatriculaController::class, 'createTutor'])->name('matriculas.create-tutor');
    Route::post('/solicitar-matricula', [MatriculaController::class, 'storeTutor'])->name('matriculas.store-tutor');
    Route::patch('/matriculas/{codigo_matricula}/aprobar', [MatriculaController::class, 'aprobar'])->name('matriculas.aprobar');
    Route::patch('/matriculas/{codigo_matricula}/rechazar', [MatriculaController::class, 'rechazar'])->name('matriculas.rechazar');
    Route::get('/obtener-grados', [MatriculaController::class, 'obtenerGrados'])->name('matriculas.obtener-grados');
    Route::get('/obtener-estudiante', [MatriculaController::class, 'obtenerEstudiante'])->name('matriculas.obtener-estudiante');
    Route::get('/obtener-secciones', [MatriculaController::class, 'obtenerSecciones'])->name('matriculas.obtener-secciones');
    Route::get('/buscar-estudiante', [MatriculaController::class, 'buscarEstudiante'])->name('matriculas.buscar-estudiante');

    //------------------------ estudiantes ---------------------------------
    Route::get('estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
    Route::get('/estudiantes/buscar', [EstudianteController::class, 'showEstudiante'])->name('estudiantes.buscar');
    Route::put('/estudiantes/{estudiante_id}', [EstudianteController::class, 'update'])->name('estudiantes.update');
    Route::post('/estudiantes/enviar-correo-tutor', [EstudianteController::class, 'enviarCorreoTutor'])->name('estudiantes.enviar-correo-tutor');
    
    //------------------------ docentes ---------------------------------
    Route::get('/docentes/buscar', [DocenteController::class, 'index'])->name('docentes.buscar');
    Route::post('/docentes', [DocenteController::class, 'store'])->name('docentes.store');
    Route::put('/docentes/{user_id}', [DocenteController::class, 'update'])->name('docentes.update');
    Route::get('/docentes/{user_id}/edit', [DocenteController::class, 'edit'])->name('docentes.edit');
    Route::delete('/docentes/{user_id}/delete', [DocenteController::class, 'destroy'])->name('docentes.destroy');
    Route::get('docente/mis-estudiantes', [DocenteController::class, 'misEstudiantes'])->name('docente.mis_estudiantes');
    Route::get('docente/ver-estudiantes/{grado_id}', [DocenteController::class, 'verEstudiantesPorGrado'])->name('docente.ver_estudiantes');

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
    Route::get('/reporte_notas/{codigo_matricula}/{id_asignatura}/show', [ReporteNotasController::class, 'estudiante_calificaciones'])->name('reporte_notas.show');
    Route::get('/reporte_notas/docente/{id_asignatura}', [ReporteNotasController::class, 'docente_view'])->name('reporte_notas.docente');
    Route::get('/reporte_notas/tutor/estudiantes', [ReporteNotasController::class, 'index_estudiantes_tutor'])->name('reporte_notas.tutor');
    Route::get('/reporte_notas/tutor/estudiantes/{id_asignatura}/asignatura', [ReporteNotasController::class, 'verNotasEstudiante'])->name('reporte_notas.tutor.estudiante');
    Route::get('/reporte-notas/pdf/{codigo_matricula}', [ReporteNotasController::class, 'generarReportePdf'])->name('reporte.notas.pdf');
    Route::resource('pagos', PagoController::class)->except(['create']);
    Route::get('/pagos/create/{matricula_id}/matricula', [PagoController::class, 'create'])->name('pagos.create');

    //----------Asistencia-----------------------
    Route::get('/asistencias', [AsistenciaController::class, 'index'])->name('asistencias.index');
    Route::get('/asistencias/create', [AsistenciaController::class, 'create'])->name('asistencias.create');
    Route::post('/asistencias', [AsistenciaController::class, 'store'])->name('asistencias.store');
    Route::get('/asistencias/secciones-por-grado', [AsistenciaController::class, 'obtenerSeccionesPorGrado'])->name('asistencias.secciones-por-grado');
    Route::get('/asistencias/{codigo_estudiante}', [AsistenciaController::class, 'show'])->name('asistencias.show');
    Route::get('/asistencias/{codigo_estudiante}/show', [AsistenciaController::class, 'showAsistenciasEstudiante'])->name('asistencias.show.estudiante');
    Route::get('/asistencias/{codigo_estudiante}/edit', [AsistenciaController::class, 'edit'])->name('asistencias.edit');
    Route::put('/asistencias/{codigo_estudiante}', [AsistenciaController::class, 'update'])->name('asistencias.update');

    // Después de las rutas de asistencias
    Route::post('/asistencias/solicitar-justificacion', [AsistenciaController::class, 'solicitarJustificacion'])->name('asistencias.solicitar-justificacion');
    Route::get('/asistencias/descargar-justificacion/{id}', [AsistenciaController::class, 'descargarJustificacion'])->name('asistencias.descargar-justificacion');
    Route::get('/asistencias/cancelar-justificacion/{id}', [AsistenciaController::class, 'cancelarJustificacion'])->name('asistencias.cancelar-justificacion');
    // ------------ Asistencia qr controller ---- 
    Route::get('/estudiantes/{id}/generate-qr', [AsistenciaQRController::class, 'showQRForm'])->name('asistencia.generate-form');
    Route::post('/estudiantes/{id}/generate-qr', [AsistenciaQRController::class, 'generateQR'])->name('asistencia.generate');
    Route::post('/asistencia/repair-qr/{id}', [AsistenciaQRController::class, 'repairQR'])->name('asistencia.repair-qr');
    Route::get('/estudiantes/{id}/qr', [AsistenciaQRController::class, 'showStudentQR'])->name('asistencia.show-qr');
    Route::get('/estudiantes/{id}/download-qr', [AsistenciaQRController::class, 'downloadQR'])->name('asistencia.download-qr');
    Route::get('/asistencia/qr-image/{id}', [AsistenciaQRController::class, 'showQRImage'])->name('asistencia.qr-image');
    Route::get('/qr-scan/{code}', [AsistenciaQRController::class, 'processShortScan']);
    
    // Ruta accesible por QR (pública)
    Route::get('/asistencia/scan/{qr_code}', function ($qrCode) {
        return view('pages.admin.asistencia.public-scan', compact('qrCode'));
    })->name('asistencia.scan');

    Route::get('/calificaciones-masivas/{id_asignatura}', [ReporteNotasController::class, 'calificacionesMasivas'])->name('reporte_notas.calificaciones-masivas');
    Route::post('/guardar-calificaciones-masivas', [ReporteNotasController::class, 'guardarCalificacionesMasivas'])->name('reporte_notas.guardar-masivas');
    Route::post('/actualizar-calificaciones-masivas', [ReporteNotasController::class, 'actualizarCalificacionesMasivas'])->name('reporte_notas.actualizar-masivas');
    // Rutas de prueba: calificar todos los periodos para una asignatura (uso docente para testing)
    Route::get('/calificar-todos/{id_asignatura}', [ReporteNotasController::class, 'calificarTodos'])->name('reporte_notas.calificar-todos');
    Route::post('/guardar-calificaciones-todos', [ReporteNotasController::class, 'guardarCalificacionesMasivasAllPeriods'])->name('reporte_notas.guardar-todos');
});