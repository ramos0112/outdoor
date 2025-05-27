<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservaMovilidad extends Model
{
    use HasFactory;

    protected $table = 'reserva_movilidads';
    protected $primaryKey = 'id_reserva_movilidad';

    protected $fillable = [
        'id_reserva',
        'id_movilidad',
    ];
}
