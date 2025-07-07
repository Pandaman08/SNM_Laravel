<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $primaryKey = 'codigo_matricula';

    public $incrementing = false;
    protected $fillable = [
        'codigo_matricula',
        'codigo_estudiante',
        'id_anio_escolar',
        'id_tipo_matricula',
        'estado',
        'seccion_id', // ← AGREGAR FK a secciones
        'fecha'
    ];

    protected $casts = [
        'codigo_estudiante' => 'integer',

    ];
    protected $dates = ['fecha'];

    public function estudiante()
    {
        return $this->belongsTo(Estudiante::class, 'codigo_estudiante', 'codigo_estudiante');
    }

    public function anioEscolar()
    {
        return $this->belongsTo(AnioEscolar::class, 'id_anio_escolar', 'id_anio_escolar');
    }

    public function tipoMatricula()
    {
        return $this->belongsTo(TipoMatricula::class, 'id_tipo_matricula', 'id_tipo_matricula');
    }

    // ← NUEVA RELACIÓN: Matricula pertenece a una Sección
    public function seccion()
    {
        return $this->belongsTo(Seccion::class, 'seccion_id', 'id_seccion');
    }

    public function detallesAsignatura()
    {
        return $this->hasMany(DetalleAsignatura::class, 'codigo_matricula', 'codigo_matricula');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'id_pago', 'id_pago');
    }
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'codigo_matricula', 'codigo_matricula');
    }
    public static function generarCodigoMatricula()
    {
        $currentYear = date('Y');
        do {
            $randomNumber = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $generatedCode = "{$currentYear}{$randomNumber}";

        } while (Matricula::where('codigo_matricula', $generatedCode)->exists());

        return $generatedCode;

    }
}