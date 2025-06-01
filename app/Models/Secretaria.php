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
        'area_responsabilidad',
        'fecha_contratacion',
        'jornada_laboral',
        'user_id'
    ];

    protected $dates = ['fecha_contratacion'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

}
