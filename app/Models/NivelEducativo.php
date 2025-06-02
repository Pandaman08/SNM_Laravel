<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NivelEducativo extends Model
{
    use HasFactory;

    protected $table = 'niveles_educativos';
    protected $primaryKey = 'id_nivel_educativo';

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'edad_minima',
        'edad_maxima',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
        'edad_minima' => 'integer',
        'edad_maxima' => 'integer'
    ];

    // Relación: Un nivel educativo tiene muchos grados
    public function grados()
    {
        return $this->hasMany(Grado::class, 'nivel_educativo_id', 'id_nivel_educativo');
    }

    // Relación: Obtener todas las secciones de este nivel (a través de grados)
    public function secciones()
    {
        return $this->hasManyThrough(
            Seccion::class,
            Grado::class,
            'nivel_educativo_id', // FK en tabla grados
            'grado_id',           // FK en tabla secciones
            'id_nivel_educativo', // PK en tabla niveles_educativos
            'id_grado'            // PK en tabla grados
        );
    }

    // Relación: Obtener todas las matrículas de este nivel (a través de secciones)
    public function matriculas()
    {
        return $this->hasManyThrough(
            Matricula::class,
            Seccion::class,
            'grado_id',           // FK en tabla secciones (a través de grado)
            'seccion_id',         // FK en tabla matriculas
            'id_nivel_educativo', // PK en tabla niveles_educativos
            'id_seccion'          // PK en tabla secciones
        )->join('grados', 'secciones.grado_id', '=', 'grados.id_grado')
          ->where('grados.nivel_educativo_id', '=', $this->id_nivel_educativo);
    }

    // Scope: Solo niveles activos
    public function scopeActivos($query)
    {
        return $query->where('activo', true);
    }
}