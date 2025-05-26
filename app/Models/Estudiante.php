<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    /** @use HasFactory<\Database\Factories\EstudianteFactory> */
    use HasFactory;

        protected $primaryKey = 'codigo_estudiante';
    protected $table = 'estudiantes';

    protected $fillable = [
        'user_id',
    ];

    protected $casts = [
        
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
