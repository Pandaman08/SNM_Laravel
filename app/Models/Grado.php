<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_grado';
    
    protected $fillable = ['grado'];

    public function asignaturas()
    {
        return $this->hasMany(Asignatura::class, 'id_grado', 'id_grado');
    }

    public function secciones()
    {
        return $this->hasMany(Seccion::class, 'id_grado', 'id_grado');
    }
}
