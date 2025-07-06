<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

    // ASOCIAR EL id_competencias aqui dentro. Como lo hago?

class Asignatura extends Model
{
    use HasFactory;
    protected $table = 'asignaturas';
    protected $primaryKey = 'codigo_asignatura';
    public $incrementing = true; // true por defecto, pero lo aclaramos por seguridad
    protected $keyType = 'int';

    protected $fillable = [
        'id_grado',
        'nombre'
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    public function seccion()
{
    return $this->belongsTo(Seccion::class, 'seccion_id');
}
    //


     public function asignaturasDocente()
    {
        return $this->hasMany(AsignaturaDocente::class, 'codigo_asignatura', 'codigo_asignatura');
    }

    public function competencias()
    {
        return $this->hasMany(Competencia::class, 'codigo_asignatura', 'codigo_asignatura');
    }

    //
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'asignaturas_docentes', 'codigo_asignatura', 'codigo_docente')
                    ->withPivot('fecha');
    }

    // public function asignaturaDocente(){
    //     return $this->hasMany(AsignaturaDocente::class, 'codigo_asignatura','codigo_asignatura');
    // }
}
