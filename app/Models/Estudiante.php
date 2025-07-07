<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    /** @use HasFactory<\Database\Factories\EstudianteFactory> */
    use HasFactory;

    protected $primaryKey = 'codigo_estudiante';
    public $incrementing = false;
    protected $table = 'estudiantes';

    protected $fillable = [
        'codigo_estudiante',
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
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'persona_id');
    }

    
    

    public static function generarCodigoEstudiante(): int
    {
        do {
            $code = random_int(1000, 9999);
        } while (self::where('codigo_estudiante', $code)->exists());

        return $code;
    }

}
