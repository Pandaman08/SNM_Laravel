<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Especialidad extends Model
{
    use HasFactory;

    protected $table = 'especialidades';
    protected $primaryKey = 'id_especialidad';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    protected $casts = [
        'id_especialidad' => 'integer',
    ];

    // Scope para especialidades activas
    public function scopeActivas($query)
    {
        return $query->where('estado', 'Activo');
    }

    // Relación muchos a muchos con Docente
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_especialidad', 'id_especialidad', 'codigo_docente')
                    ->wherePivot('estado', 'Activo')
                    ->withPivot('estado', 'codigo_docente', 'id_especialidad');
    }
}
