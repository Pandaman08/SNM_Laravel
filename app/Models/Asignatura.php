<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asignatura extends Model
{
    use HasFactory;

    protected $primaryKey = 'codigo_asignatura';
    
    protected $fillable = [
        'id_grado',
        'nombre'
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    public function detallesAsignatura()
    {
        return $this->hasMany(DetalleAsignatura::class, 'codigo_asignatura', 'codigo_asignatura');
    }

    public function competencias()
    {
        return $this->hasMany(Competencia::class, 'codigo_asignatura', 'codigo_asignatura');
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'asignaturas_docentes', 'codigo_asignatura', 'codigo_docente')
                    ->withPivot('fecha');
    }
}
