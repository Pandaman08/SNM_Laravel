<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\AsistenciaEstado;

class Asistencia extends Model
{
    use HasFactory;
    
    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';

    protected $fillable = [
        'codigo_estudiante',
        'id_periodo',
        'fecha',
        'hora_entrada',
        'hora_salida',
        'estado',
        'observacion',
        'justificacion',
        'archivo_justificacion',                 // ⭐ AGREGAR
        'archivo_justificacion_original',        // ⭐ AGREGAR
        'tipo_registro',
        'estado_justificacion',
        'motivo_rechazo',
        'fecha_solicitud_justificacion',
        'fecha_revision_justificacion',
        'revisado_por'
    ];

    protected $casts = [
        'fecha' => 'date',
        'estado' => AsistenciaEstado::class,
        'fecha_solicitud_justificacion' => 'datetime',   // ← AGREGAR
        'fecha_revision_justificacion' => 'datetime',    // ← AGREGAR
    ];

    // Relaciones
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'codigo_estudiante', 'codigo_estudiante');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo', 'id_periodo');
    }
    
    // ← AGREGAR esta relación
    public function revisadoPor()
    {
        return $this->belongsTo(User::class, 'revisado_por', 'user_id');
    }
    
    // Accessor para hora_entrada
    public function getHoraEntradaFormattedAttribute()
    {
        return $this->hora_entrada ? \Carbon\Carbon::createFromFormat('H:i:s', $this->hora_entrada)->format('h:i A') : null;
    }
    
    // Accessor para hora_salida
    public function getHoraSalidaFormattedAttribute()
    {
        return $this->hora_salida ? \Carbon\Carbon::createFromFormat('H:i:s', $this->hora_salida)->format('h:i A') : null;
    }
}