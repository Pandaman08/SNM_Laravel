<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    /** @use HasFactory<\Database\Factories\EstudianteFactory> */
    use HasFactory;

    protected $primaryKey = 'codigo_estudiante';
    protected $table = 'estudiantes';

    protected $fillable = [
        'persona_id',
        'pais',
        'provincia',
        'distrito',
        'departamento',
        'lengua_materna',
        'religion',
        'estado_civil'
    ];

    

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'codigo_estudiante', 'codigo_estudiante');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'codigo_estudiante', 'codigo_estudiante');
    }

    public function tutores()
    {
        return $this->belongsToMany(Tutor::class, 'estudiantes_tutores', 'codigo_estudiante', 'id_tutor')
                    ->withPivot('tipo_relacion');
    }
}
