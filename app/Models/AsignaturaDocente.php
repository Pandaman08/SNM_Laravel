<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignaturaDocente extends Model
{ 
    use HasFactory;
    protected $table = 'asignaturas_docentes';
    protected $primaryKey = 'id_asignatura_docente';
    public $incrementing = true; // true por defecto, pero lo aclaramos por seguridad
    protected $keyType = 'int';

    protected $fillable = [
        'codigo_asignatura',
        'codigo_docente'
    ];

    public function asignatura(){
        return $this->belongsTo(Asignatura::class, 'codigo_asignatura', 'codigo_asignatura');
    }
     public function docente()
    {
        return $this->belongsTo(Docente::class, 'codigo_docente', 'codigo_docente');
    }

}
