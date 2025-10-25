<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionEducativa extends Model
{
    use HasFactory;

    protected $table = 'institucion_educativa';
    protected $primaryKey = 'codigo_modular';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'codigo_modular',
        'user_id',
        'dre',
        'ugel',
        'logo_ie',
        'nombre_colegio',
        'codigo_local_ie',
        'modalidad_ie',
        'genero_ie',
        'turno_ie',
        'direccion_ie',
        'departamento_ie',
        'provincia_ie',
        'distrito_ie',
    ];

    // Relación con User (administrador)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Relación con Matrículas
    public function matriculas()
    {
        return $this->hasMany(Matricula::class, 'institucion_educativa_codigo_modular', 'codigo_modular');
    }

    // Método helper: Obtener dirección completa
    public function getDireccionCompletaAttribute()
    {
        return "{$this->direccion_ie}, {$this->distrito_ie}, {$this->provincia_ie}, {$this->departamento_ie}";
    }

    // Método helper: Verificar si tiene logo
    public function tieneLogoAttribute()
    {
        return !empty($this->logo_ie);
    }
}