<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $table = 'tutores';
    protected $primaryKey = 'id_tutor';

    protected $fillable = [
        'user_id',
        'parentesco',
        'lugar_trabajo',
        'oficio',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relación many-to-many con Estudiantes a través de estudiantes_tutores
    public function estudiantes()
    {
        return $this->belongsToMany(Estudiante::class, 'estudiantes_tutores', 'id_tutor', 'codigo_estudiante')
                    ->withPivot('tipo_relacion')
                    ->withTimestamps();
    }

    // Relación directa con tabla pivot estudiantes_tutores
    public function estudiantesTutores()
    {
        return $this->hasMany(EstudianteTutor::class, 'id_tutor', 'id_tutor');
    }

    // Relación con Parientes del Tutor
    public function parientes()
    {
        return $this->hasMany(ParienteTutor::class, 'tutor_id_tutor', 'id_tutor');
    }
}