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
        'firma_docente',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function nivelEducativo()
    {
        return $this->belongsTo(NivelEducativo::class, 'nivel_educativo_id', 'id_nivel_educativo');
    }
    
    // Relación con Asignaturas a través de asignaturas_docentes
    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'asignaturas_docentes', 'codigo_docente', 'codigo_asignatura')
                    ->withPivot('fecha')
                    ->withTimestamps();
    }
    
    // Relación con Secciones (solo activas)
    public function secciones()
    {
        return $this->belongsToMany(Seccion::class, 'secciones_docentes', 'codigo_docente', 'id_seccion')
                    ->withPivot('estado')
                    ->withTimestamps()
                    ->wherePivot('estado', 1);
    }
    
    // Todas las secciones asignadas (activas e inactivas)
    public function todasLasSecciones()
    {
        return $this->belongsToMany(Seccion::class, 'secciones_docentes', 'codigo_docente', 'id_seccion')
                    ->withPivot('estado')
                    ->withTimestamps();
    }
    
    // Relación directa con tabla pivot secciones_docentes
    public function seccionesDocentes()
    {
        return $this->hasMany(SeccionDocente::class, 'codigo_docente', 'codigo_docente');
    }
    
    // Obtener estudiantes matriculados en secciones del docente
    public function estudiantes()
    {
        $seccionIds = $this->secciones()->pluck('id_seccion');
        
        return Estudiante::whereHas('matriculas', function($query) use ($seccionIds) {
            $query->whereIn('seccion_id', $seccionIds)
                  ->where('estado', 'activo');
        });
    }

    // Obtener matrículas de las secciones del docente
    public function matriculas()
    {
        return Matricula::whereIn('seccion_id', $this->secciones()->pluck('id_seccion'))
                       ->where('estado', 'activo');
    }
}