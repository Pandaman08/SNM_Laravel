<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParienteTutor extends Model
{
    use HasFactory;

    protected $table = 'pariente_tutor';
    protected $primaryKey = 'id_pariente_tutor';

    protected $fillable = [
        'tutor_id_tutor',
        'nombre_pariente_tutor',
        'celular_pariente_tutor',
    ];

    // Relación con Tutor
    public function tutor()
    {
        return $this->belongsTo(Tutor::class, 'tutor_id_tutor', 'id_tutor');
    }

    // Validar formato de celular (9 dígitos)
    public function getCelularFormateadoAttribute()
    {
        if (strlen($this->celular_pariente_tutor) === 9) {
            return substr($this->celular_pariente_tutor, 0, 3) . ' ' . 
                   substr($this->celular_pariente_tutor, 3, 3) . ' ' . 
                   substr($this->celular_pariente_tutor, 6, 3);
        }
        return $this->celular_pariente_tutor;
    }
}