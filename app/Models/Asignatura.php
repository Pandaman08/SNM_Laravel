<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// ASOCIAR EL id_competencias aqui dentro. Como lo hago?

class Asignatura extends Model
{
    use HasFactory;
    protected $table = 'asignaturas';
    protected $primaryKey = 'codigo_asignatura';
    public $incrementing = true; // true por defecto, pero lo aclaramos por seguridad
    protected $keyType = 'int';

    protected $fillable = [
        'id_grado',
        'nombre'
    ];

    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }

    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'seccion_id');
    }
    //


    public function asignaturasDocente()
    {
        return $this->hasMany(AsignaturaDocente::class, 'codigo_asignatura', 'codigo_asignatura');
    }

    public function competencias()
    {
        return $this->hasMany(Competencia::class, 'codigo_asignatura', 'codigo_asignatura');
    }

    //
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'asignaturas_docentes', 'codigo_asignatura', 'codigo_docente')
            ->withPivot('fecha'); // Solo especificar la columna fecha
    }

    // NUEVA RELACIÓN: Especialidades permitidas para esta asignatura
    public function especialidadesPermitidas()
    {
        return $this->belongsToMany(
            Especialidad::class,
            'asignatura_especialidad',
            'codigo_asignatura',
            'id_especialidad'
        )->withPivot('estado');
    }

    // Método para obtener docentes con especialidades compatibles
    public function docentesCompatibles()
    {
        // Obtener especialidades requeridas para esta asignatura
        $especialidadesRequeridas = $this->especialidadesPermitidas()
            ->where('asignatura_especialidad.estado', 'Activo')
            ->pluck('id_especialidad');

        if ($especialidadesRequeridas->isEmpty()) {
            return Docente::with(['user.persona', 'especialidades'])->get();
        }

        return Docente::with(['user.persona', 'especialidades'])
            ->whereHas('especialidades', function ($query) use ($especialidadesRequeridas) {
                $query->whereIn('especialidades.id_especialidad', $especialidadesRequeridas)
                    ->where('docente_especialidad.estado', 'Activo');
            })
            ->get();
    }
}
