<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $primaryKey = 'codigo_matricula';
    
    protected $fillable = [
        'codigo_estudiante',
        'id_anio_escolar',
        'id_tipo_matricula',
        'seccion_id', // ← AGREGAR FK a secciones
        'fecha'
    ];

    protected $dates = ['fecha'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'codigo_estudiante', 'codigo_estudiante');
    }

    public function anioEscolar()
    {
        return $this->belongsTo(AnioEscolar::class, 'id_anio_escolar', 'id_anio_escolar');
    }

    public function tipoMatricula()
    {
        return $this->belongsTo(TipoMatricula::class, 'id_tipo_matricula', 'id_tipo_matricula');
    }

    // ← NUEVA RELACIÓN: Matricula pertenece a una Sección
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'id_seccion');
    }

    public function detallesAsignatura()
    {
        return $this->hasMany(DetalleAsignatura::class, 'codigo_matricula', 'codigo_matricula');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'codigo_matricula', 'codigo_matricula');
    }
}