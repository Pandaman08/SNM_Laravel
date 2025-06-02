<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstudianteTutor extends Model
{
    protected $table = 'estudiantes_tutores';
    
    // Como tienes clave primaria compuesta, Laravel necesita esto
    protected $primaryKey = ['codigo_estudiante', 'id_tutor'];
    public $incrementing = false;
    
    protected $fillable = [
        'codigo_estudiante',
        'id_tutor', 
        'tipo_relacion'
    ];

    protected $casts = [
        'codigo_estudiante' => 'integer',
        'id_tutor' => 'integer',
    ];

    // Relación con Estudiante
    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'codigo_estudiante', 'codigo_estudiante');
    }

    // Relación con Tutor  
    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'id_tutor', 'id_tutor');
    }
}