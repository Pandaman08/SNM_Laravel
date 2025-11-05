<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteNota extends Model
{
    use HasFactory;

    protected $table = 'reportes_notas';
    protected $primaryKey = 'id_calificacion';

    protected $fillable = [
        'id_detalle_asignatura',
        'id_periodo',
        'observacion',
        'fecha_registro',
        'calificacion_periodo',
    ];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    // Relación con DetalleAsignatura
    public function detalleAsignatura()
    {
        return $this->belongsTo(DetalleAsignatura::class, 'id_detalle_asignatura', 'id_detalle_asignatura');
    }

    // Relación con Periodo
    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo', 'id_periodo');
    }
}