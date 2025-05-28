<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMatricula extends Model
{
    use HasFactory;

    protected $table = 'tipos_matricula';
    protected $primaryKey = 'id_tipo_matricula';
    
    protected $fillable = [
        'nombre',
        'descripcion'
    ];

    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'id_tipo_matricula', 'id_tipo_matricula');
    }
}
