<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movilidad extends Model
{
    use HasFactory;

    protected $table = 'movilidads';

    protected $primaryKey = 'id_movilidad';

    protected $fillable = [
        'ruta',
        'tipo_movilidad',
        'capacidad',
        'estado',
    ];

    public function reservas()
    {
        return $this->belongsToMany(Reserva::class, 'reserva_movilidads', 'id_movilidad', 'id_reserva');
    }

    public function guias()
    {
        return $this->belongsToMany(Guia::class, 'movilidad_guias', 'id_movilidad', 'id_guia');
    }
}

