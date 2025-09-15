<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    /** @use HasFactory<\Database\Factories\PagoFactory> */
     use HasFactory;

    protected $primaryKey = 'id_pago';
    protected $table = 'pagos';

    protected $fillable = [
        'codigo_matricula',
        'concepto',
        'monto',
        'fecha_pago',
        'comprobante_img',
        'estado',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'codigo_matricula');
    }

}
