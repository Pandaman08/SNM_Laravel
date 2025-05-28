<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

}
