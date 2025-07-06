<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SeccionDocente extends Pivot
{
    use HasFactory;

    protected $table = 'secciones_docentes';
    
    public $incrementing = false;
    
    protected $fillable = [
        'id_seccion',
        'codigo_docente',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // Relación con Seccion
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'id_seccion', 'id_seccion');
    }

    // Relación con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'codigo_docente', 'codigo_docente');
    }

    // Scope para obtener solo asignaciones activas
    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    // Scope para obtener asignaciones por grado
    public function scopePorGrado($query, $gradoId)
    {
        return $query->whereHas('seccion', function ($q) use ($gradoId) {
            $q->where('id_grado', $gradoId);
        });
    }

    // Método para activar/desactivar asignación
    public function toggleEstado()
    {
        $this->estado = !$this->estado;
        $this->save();
        return $this;
    }

    // Método para verificar si la asignación está activa
    public function estaActiva()
    {
        return $this->estado === true;
    }
}