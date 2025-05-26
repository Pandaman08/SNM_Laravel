<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secretaria extends Model
{
    /** @use HasFactory<\Database\Factories\SecretariaFactory> */
    use HasFactory;
      protected $primaryKey = 'codigo_secretaria';
    protected $table = 'secretarias';

    protected $fillable = [
        'user_id',
        'fecha_contratacion',
        'area_responsabilidad',
        'jornada_laboral',
    ];

    protected $casts = [
        'fecha_contratacion' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
