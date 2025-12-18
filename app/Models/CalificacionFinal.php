<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionFinal extends Model
{
    use HasFactory;

    protected $table = 'calificaciones_finales';
    protected $primaryKey = 'id_calificacion_final';

    protected $fillable = [
        'codigo_matricula',
        'codigo_asignatura',
        'calificacion_final',
        'fecha_registro',
    ];
}
