<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class SeccionDocente extends Pivot
{
    use HasFactory;

    protected $table = 'secciones_docentes';

    // Si la tabla no tiene una columna id auto-incremental:
    protected $primaryKey = null;
    public $incrementing = false;

    // IMPORTANTE: si la tabla tiene created_at/updated_at
    public $timestamps = true;

    protected $fillable = [
        'id_seccion',
        'codigo_docente',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    // relaciones y scopes (como tenías)
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'id_seccion', 'id_seccion');
    }

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'codigo_docente', 'codigo_docente');
    }

    public function scopeActivos($query)
    {
        return $query->where('estado', true);
    }

    public function scopePorGrado($query, $gradoId)
    {
        return $query->whereHas('seccion', function ($q) use ($gradoId) {
            $q->where('id_grado', $gradoId);
        });
    }

    public function toggleEstado()
    {
        $this->estado = !$this->estado;
        $this->save();
        return $this;
    }

    public function estaActiva()
    {
        return $this->estado === true;
    }
}
