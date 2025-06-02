<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use App\Models\Grado; 

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones';
    protected $primaryKey = 'id_seccion';

    protected $fillable = [
        'id_grado',  // Tu FK
        'seccion'    // Tu campo nombre de sección
    ];

    // Relación: Sección pertenece a un grado
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    // Relación: Sección tiene muchas matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'seccion_id', 'id_seccion');
    }

    // Relación: Obtener el nivel educativo a través del grado
    public function nivelEducativo()
    {
        return $this->hasOneThrough(
            NivelEducativo::class,
            Grado::class,
            'id_grado',              // FK en tabla grados
            'id_nivel_educativo',    // PK en tabla niveles_educativos
            'id_grado',              // FK local en tabla secciones
            'id_nivel_educativo'     // FK local en tabla grados
        );
    }

    // Scope: Secciones de un grado específico
    public function scopeDeGrado($query, $gradoId)
    {
        return $query->where('id_grado', $gradoId);
    }

    // Scope: Secciones de un nivel educativo específico
    public function scopeDeNivel($query, $nivelId)
    {
        return $query->whereHas('grado', function ($q) use ($nivelId) {
            $q->where('id_nivel_educativo', $nivelId);
        });
    }

    // Método: Obtener nombre completo de la sección
    public function getNombreCompletoAttribute()
    {
        $grado = $this->grado;
        if ($grado && $grado->nivelEducativo) {
            return "Sección {$this->seccion} - {$grado->nombre_completo}";
        }
        return "Sección {$this->seccion}";
    }
}