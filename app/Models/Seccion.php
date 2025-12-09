<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seccion extends Model
{
    use HasFactory;

    protected $table = 'secciones';
    protected $primaryKey = 'id_seccion';

    protected $fillable = [
        'id_grado',
        'seccion',
        'vacantes_seccion',
        'estado_vacantes',
    ];

    protected $casts = [
        'vacantes_seccion' => 'integer',
        'estado_aforo' => 'boolean',
    ];

    // Relación con Grado
    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    // Relación con Matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'seccion_id', 'id_seccion');
    }

    // Relación con Docentes (solo activos)
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'secciones_docentes', 'id_seccion', 'codigo_docente')
                    ->withPivot('estado')
                    ->withTimestamps()
                    ->wherePivot('estado', 1);
    }
    
    // Todos los docentes asignados (activos e inactivos)
    public function todosLosDocentes()
    {
        return $this->belongsToMany(Docente::class, 'secciones_docentes', 'id_seccion', 'codigo_docente')
                    ->withPivot('estado')
                    ->withTimestamps();
    }
    
    // Relación directa con tabla pivot secciones_docentes
    public function seccionesDocentes()
    {
        return $this->hasMany(SeccionDocente::class, 'id_seccion', 'id_seccion');
    }

    // Nivel educativo a través del grado
    public function nivelEducativo()
    {
        return $this->hasOneThrough(
            NivelEducativo::class,
            Grado::class,
            'id_grado',
            'id_nivel_educativo',
            'id_grado',
            'nivel_id_nivel'
        );
    }

    // Scope: Secciones de un grado
    public function scopeDeGrado($query, $gradoId)
    {
        return $query->where('id_grado', $gradoId);
    }

    // Scope: Secciones de un nivel educativo
    public function scopeDeNivel($query, $nivelId)
    {
        return $query->whereHas('grado', function ($q) use ($nivelId) {
            $q->where('nivel_id_nivel', $nivelId);
        });
    }

    // Atributo: Nombre completo
    public function getNombreCompletoAttribute()
    {
        $grado = $this->grado;
        if ($grado && $grado->nivelEducativo) {
            return "Sección {$this->seccion} - {$grado->grado} {$grado->nivelEducativo->nombre_nivel_educativo}";
        }
        return "Sección {$this->seccion}";
    }

    // Verificar si hay cupo disponible
    public function tieneCupo()
    {
        return $this->estado_aforo == 1;
    }

    public function reducirVacante()
    {
        if ($this->vacantes_seccion > 0) {
            $this->vacantes_seccion -= 1;
            if ($this->vacantes_seccion == 0) {
                $this->estado_aforo = 0; // No hay cupo
            }
            $this->save();
        }
    }

    // Obtener cantidad de estudiantes matriculados
    public function cantidadEstudiantes()
    {
        return $this->matriculas()->where('estado', 'activo')->count();
    }

    // Obtener seccion de un docente y grado específico
    public static function obtenerSeccionPorDocenteYGrado($codigoDocente, $idGrado)
    {
        return self::whereHas('seccionesDocentes', function ($query) use ($codigoDocente) {
            $query->where('codigo_docente', $codigoDocente)
                  ->where('estado', 1); // Solo activos
        })->where('id_grado', $idGrado)->first();
    }
}