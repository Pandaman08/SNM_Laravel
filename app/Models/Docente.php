<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    protected $primaryKey = 'codigo_docente';
    protected $table = 'docentes';

    protected $fillable = [
        'user_id',
        'especialidad',
        'jornada_laboral',
        'fecha_contratacion',
        'departamento_estudios',
    ];

    protected $dates = ['fecha_contratacion'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'asignaturas_docentes', 'codigo_docente', 'codigo_asignatura')
                    ->withPivot('fecha');
    }
    
    // Relación con secciones (solo activas)
    public function secciones()
    {
        return $this->belongsToMany(Seccion::class, 'secciones_docentes', 'codigo_docente', 'id_seccion')
                    ->using(SeccionDocente::class)
                    ->withPivot('estado')
                    ->withTimestamps()
                    ->wherePivot('estado', true);
    }
    
    // Relación para obtener TODAS las secciones (activas e inactivas)
    public function todasLasSecciones()
    {
        return $this->belongsToMany(Seccion::class, 'secciones_docentes', 'codigo_docente', 'id_seccion')
                    ->using(SeccionDocente::class)
                    ->withPivot('estado')
                    ->withTimestamps();
    }
    
    // Relación directa con la tabla pivot
    public function asignacionesSecciones()
    {
        return $this->hasMany(SeccionDocente::class, 'codigo_docente', 'codigo_docente');
    }
    
    // Método helper: Obtener estudiantes de las secciones del docente
    public function estudiantes()
    {
        return Matricula::whereIn('seccion_id', $this->secciones()->pluck('id_seccion'))
                       ->where('estado', true)
                       ->with('estudiante.persona');
    }
}