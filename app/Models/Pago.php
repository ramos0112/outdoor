<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $primaryKey = 'id_pago';

    protected $fillable = [
        'id_reserva',
        'metodo_pago',
        'monto_pagado',
        'fecha_pago',
    ];

    public function reserva()
    {
        #return $this->belongsTo(Reserva::class, 'id_reserva', 'id_reserva');
     return $this->hasMany(Pago::class, 'id_reserva', 'id_reserva');    }
}
