<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    /** @use HasFactory<\Database\Factories\TutorFactory> */
    use HasFactory;
    protected $table = 'tutores';
    protected $primaryKey = 'id_tutor';
    
    protected $fillable = [
        'user_id',
        'direccion',
        'seccion'
    ];

}
