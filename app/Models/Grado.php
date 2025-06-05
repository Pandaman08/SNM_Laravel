<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_grado';

    protected $fillable = [
        'nivel_educativo_id', // ✅ CORREGIDO: Este es el campo real en tu BD
        'grado'               // Número del grado según el nivel
    ];

    protected $casts = [
        'grado' => 'integer'
    ];

    // Relación: Grado pertenece a un nivel educativo
    public function nivelEducativo()
    {
        return $this->belongsTo(NivelEducativo::class, 'nivel_educativo_id', 'id_nivel_educativo');
    }

    // Relación: Un grado tiene muchas secciones
    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'grado_id', 'id_grado');
    }

    // Relación: Un grado tiene muchas asignaturas
    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'grado_id', 'id_grado');
    }

    // Relación: Obtener todas las matrículas de este grado (a través de secciones)
    public function matriculas()
    {
        return $this->hasManyThrough(
            Matricula::class,
            Seccion::class,
            'grado_id',     // FK en tabla secciones
            'seccion_id',   // FK en tabla matriculas
            'id_grado',     // PK en tabla grados
            'id_seccion'    // PK en tabla secciones
        );
    }

    // Método: Obtener nombre completo del grado
    public function getNombreCompletoAttribute()
    {
        $nivel = $this->nivelEducativo;
        
        if (!$nivel) return "Grado {$this->grado}";
        
        switch ($nivel->codigo) {
            case 'INI':
                return "{$this->grado} años";
            case 'PRI':
                return "{$this->grado}° Primaria";
            case 'SEC':
                return "{$this->grado}° Secundaria";
            default:
                return "{$this->grado}° {$nivel->nombre}";
        }
    }

    // Scope: Grados de un nivel específico
    public function scopeDeNivel($query, $nivelId)
    {
        return $query->where('nivel_educativo_id', $nivelId); // ✅ CORREGIDO
    }

    // Scope: Grados de inicial
    public function scopeInicial($query)
    {
        return $query->whereHas('nivelEducativo', function($q) {
            $q->where('codigo', 'INI');
        });
    }

    // Scope: Grados de primaria
    public function scopePrimaria($query)
    {
        return $query->whereHas('nivelEducativo', function($q) {
            $q->where('codigo', 'PRI');
        });
    }

    // Scope: Grados de secundaria
    public function scopeSecundaria($query)
    {
        return $query->whereHas('nivelEducativo', function($q) {
            $q->where('codigo', 'SEC');
        });
    }
}