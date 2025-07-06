<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AsistenciaEstado;

class Asistencia extends Model
{
    /** @use HasFactory<\Database\Factories\AsistenciaFactory> */
    use HasFactory;
    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';

    protected $fillable = [
        'codigo_estudiante',
        'id_periodo',
        'fecha',
        'estado',
        'observacion',
        'justificacion'
    ];

    protected $casts = [
        'fecha' => 'date',
        'estado' => AsistenciaEstado::class,
    ];

    // Corregidas las relaciones: debe ser belongsTo, no hasOne
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'codigo_estudiante', 'codigo_estudiante');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo', 'id_periodo');
    }
}