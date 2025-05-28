<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_periodo';
    
    protected $fillable = [
        'numero_periodo',
        'nombre',
        'fecha_inicio',
        'fecha_final',
        'estado'
    ];

    protected $dates = ['fecha_inicio', 'fecha_final'];

    public function reportesNotas()
    {
        return $this->hasMany(ReporteNota::class, 'id_periodo', 'id_periodo');
    }

    public function asistencias()
    {
        return $this->hasMany(Asistencia::class, 'id_periodo', 'id_periodo');
    }
}
