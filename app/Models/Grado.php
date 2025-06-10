<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Grado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_grado';

    protected $fillable = [
        'nivel_educativo_id',
        'grado',
    ];

    protected $casts = [
        'grado'   => 'integer',
    ];

    public function nivelEducativo()
    {
        return $this->belongsTo(NivelEducativo::class, 'nivel_educativo_id', 'id_nivel_educativo');
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'id_grado', 'id_grado');
    }

    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'id_grado', 'id_grado');
    }

    public function matriculas()
    {
        return $this->hasManyThrough(
            Matricula::class,
            Seccion::class,
            'id_grado',
            'seccion_id',
            'id_grado',
            'id_seccion'
        );
    }

    public function getNombreCompletoAttribute()
    {
        $nivel = $this->nivelEducativo;
        if (! $nivel) return "Grado {$this->grado}";

        switch ($nivel->codigo) {
            case 'INI':  return "{$this->grado} años";
            case 'PRI':  return "{$this->grado}° Primaria";
            case 'SEC':  return "{$this->grado}° Secundaria";
            default:     return "{$this->grado}° {$nivel->nombre}";
        }
    }

    public function scopeDeNivel($query, $nivelId)
    {
        return $query->where('nivel_educativo_id', $nivelId);
    }

    public function scopeInicial($query)
    {
        return $query->whereHas('nivelEducativo', fn($q) => $q->where('codigo','INI'));
    }

    public function scopePrimaria($query)
    {
        return $query->whereHas('nivelEducativo', fn($q) => $q->where('codigo','PRI'));
    }

    public function scopeSecundaria($query)
    {
        return $query->whereHas('nivelEducativo', fn($q) => $q->where('codigo','SEC'));
    }
    
}