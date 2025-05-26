<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    /** @use HasFactory<\Database\Factories\DocenteFactory> */
    use HasFactory;
     protected $primaryKey = 'codigo_docente';
    protected $table = 'docentes';

    protected $fillable = [
        'user_id',
        'especialidad',
        'fecha_contratacion',
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
