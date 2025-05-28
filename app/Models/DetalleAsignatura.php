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
        'codigo_asignatura',
        'codigo_matricula',
        'fecha'
    ];

    protected $dates = ['fecha'];

    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'codigo_asignatura', 'codigo_asignatura');
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'codigo_matricula', 'codigo_matricula');
    }

    public function reportesNotas()
    {
        return $this->hasMany(ReporteNota::class, 'id_detalle_asignatura', 'id_detalle_asignatura');
    }
}
