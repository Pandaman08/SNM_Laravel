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

    // Relación con Competencia
    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'competencias_id_competencias', 'id_competencias');
    }

    // Relación con Matrícula
    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'matricula_codigo_matricula', 'codigo_matricula');
    }

    // Relación con Reportes de Notas
    public function reportesNotas()
    {
        return $this->hasMany(ReporteNota::class, 'detalle_asignatura_id_detalle_asignatura', 'id_detalle_asignatura');
    }

    // Obtener estudiante a través de matrícula
    public function estudiante()
    {
        return $this->hasOneThrough(
            Estudiante::class,
            Matricula::class,
            'codigo_matricula',
            'codigo_estudiante',
            'matricula_codigo_matricula',
            'estudiante_codigo_estudiante'
        );
    }

    // Obtener asignatura a través de competencia
    public function asignatura()
    {
        return $this->hasOneThrough(
            Asignatura::class,
            Competencia::class,
            'id_competencias',
            'codigo_asignatura',
            'competencias_id_competencias',
            'asignatura_codigo_asignatura'
        );
    }
}