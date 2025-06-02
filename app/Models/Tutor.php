<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // ⬅ Asegúrate de importar el modelo User

class Tutor extends Model
{
    use HasFactory;

    protected $table = 'tutores';
    protected $primaryKey = 'id_tutor';

    protected $fillable = [
        'user_id',
        'parentesco',
    ];

    /**
     * Relación: cada Tutor pertenece a un User.
     * Ajusta el segundo y tercer parámetro según tu migración:
     *  - 'user_id' es la fk en tutores
     *  - 'user_id' (o 'id' si tu tabla users usa ese nombre) es la pk en users
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}

