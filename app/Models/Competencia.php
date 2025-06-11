<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competencia extends Model
{
     use HasFactory;
    protected $table = 'competencias';
    protected $primaryKey = 'id_competencias';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'codigo_asignatura',
        'descripcion',
    ];

    // Relación inversa: una competencia pertenece a una asignatura
    public function asignatura()
    {
        return $this->belongsTo(Asignatura::class, 'codigo_asignatura', 'codigo_asignatura');
    }

     public function detallesAsignatura()
    {
        return $this->hasMany(DetalleAsignatura::class, 'id_detalle_asignatura', 'id_detalle_asignatura');
    }
}
