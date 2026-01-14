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
    public $timestamps = false;

    protected $fillable = [
        'codigo_asignatura',
        'codigo_docente',
        'fecha'
    ];

    public function asignatura(){
        return $this->belongsTo(Asignatura::class, 'codigo_asignatura', 'codigo_asignatura');
    }

     public function docentes(){
    
        return $this->belongsToMany(Docente::class, 'asignaturas_docentes', 'codigo_asignatura', 'codigo_docente')
                ->withPivot('fecha');
    }
}
