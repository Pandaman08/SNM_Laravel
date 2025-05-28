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
        'id_tipo_calificacion',
        'id_periodo',
        'observacion',
        'fecha_registro'
    ];

}
