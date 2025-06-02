<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Grado; 

class Seccion extends Model
{
     protected $table = 'secciones';
    protected $primaryKey = 'id_seccion';
    
    protected $fillable = [
        'id_grado',
        'seccion'
    ];


    public function grado()
    {
        return $this->belongsTo(Grado::class, 'id_grado', 'id_grado');
    }
}


