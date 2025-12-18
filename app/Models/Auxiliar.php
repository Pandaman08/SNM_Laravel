<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auxiliar extends Model
{
    use HasFactory;

    protected $table = 'auxiliares';
    protected $primaryKey = 'id_auxiliar';

    protected $fillable = [
        'user_id',
        'turno'
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}