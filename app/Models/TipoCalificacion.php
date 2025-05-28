<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoCalificacion extends Model
{
    use HasFactory;

    protected $table = 'tipos_calificacion';
    protected $primaryKey = 'id_tipo_calificacion';
    
    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion'
    ];

    public function reportesNotas()
    {
        return $this->hasMany(ReporteNota::class, 'id_tipo_calificacion', 'id_tipo_calificacion');
    }
}
