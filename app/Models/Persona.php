<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    /** @use HasFactory<\Database\Factories\PersonaFactory> */
    use HasFactory;
     protected $table = 'personas';
     protected $primaryKey = 'persona_id';
    protected $fillable = [
        'name',
        'lastname',
        'dni',
        'phone',
        'sexo',
        'photo',
        'address',
        'fecha_nacimiento',
        'estado_civil'
    ];

     protected $casts = [
        'fecha_nacimiento' => 'date'
    ];

    /*  public function user()
    {
        return $this->hasOne(User::class, 'persona_id', 'persona_id'); 
    } */
}
