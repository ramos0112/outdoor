<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaCliente extends Model
{
    use HasFactory;

    protected $table = 'reserva_clientes';
    protected $primaryKey = 'id_reserva';

    protected $fillable = [
        'id_reserva',
        'id_cliente',
    ];
}

