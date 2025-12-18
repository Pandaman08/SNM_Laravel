<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleAsignatura extends Model
{
    use HasFactory;

    protected $table = 'detalles_asignatura';
    protected $primaryKey = 'id_detalle_asignatura';

    protected $fillable = [
        'codigo_matricula',
        'id_competencias',
        'fecha',
        'calificacion_anual',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    // Relación con Competencia - CORREGIDA
    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'id_competencias', 'id_competencias');
    }

    // Relación con Matrícula - CORREGIDA
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'codigo_matricula', 'codigo_matricula');
    }

    // Relación con Reportes de Notas - CORREGIDA
    public function reportesNotas()
    {
        return $this->hasMany(ReporteNota::class, 'id_detalle_asignatura', 'id_detalle_asignatura');
    }

    // Obtener estudiante a través de matrícula - CORREGIDA
    public function estudiante()
    {
        return $this->hasOneThrough(
            Estudiante::class,
            Matricula::class,
            'codigo_matricula',
            'codigo_estudiante',
            'codigo_matricula',
            'codigo_estudiante'
        );
    }

    // Obtener asignatura a través de competencia - CORREGIDA
    public function asignatura()
    {
        return $this->hasOneThrough(
            Asignatura::class,
            Competencia::class,
            'id_competencias',
            'codigo_asignatura',
            'id_competencias',
            'codigo_asignatura'
        );
    }
}