<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;
    protected $primaryKey = 'codigo_docente';
    protected $table = 'docentes';

    protected $fillable = [
        'user_id',
        'especialidad',
        'jornada_laboral',
        'fecha_inicio',
        'departamento_estudios',
        'estado_civil',
        'fecha_fin'
    ];

    protected $dates = ['fecha_inicio', 'fecha_fin'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function asignaturas()
    {
        return $this->belongsToMany(Asignatura::class, 'asignaturas_docentes', 'codigo_docente', 'codigo_asignatura')
                    ->withPivot('fecha');
    }
}
