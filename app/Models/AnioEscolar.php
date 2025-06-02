<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnioEscolar extends Model
{
    use HasFactory;

    protected $table = 'anios_escolares';
    protected $primaryKey = 'id_anio_escolar';
    
    protected $fillable = [
        'anio',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    protected $dates = ['fecha_inicio', 'fecha_fin'];

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_anio_escolar', 'id_anio_escolar');
    }
   
}
