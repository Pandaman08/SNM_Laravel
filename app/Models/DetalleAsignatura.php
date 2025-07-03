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
        'id_competencias',  // Actualizado
        'codigo_matricula',
        'fecha'
    ];

    protected $dates = ['fecha'];

    public function competencia()
    {
        return $this->belongsTo(Competencia::class, 'id_competencias', 'id_competencias');
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
