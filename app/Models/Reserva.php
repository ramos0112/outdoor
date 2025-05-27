<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $table = 'reservas';
    protected $primaryKey = 'id_reserva';
    protected $keyType = 'int';
    public $incrementing = true;


    protected $fillable = [
        'id_fecha',
        'fecha_reserva',
        'cantidad_personas',
        'precio_total',
        'saldo',
        'estado',
    ];

    public function fechaDisponible()
    {
        return $this->belongsTo(FechaDisponible::class, 'id_fecha');
    }

    public function clientes()
    {
        return $this->belongsToMany(Cliente::class, 'reserva_clientes', 'id_reserva', 'id_cliente');
    }

    public function movilidads()
    {
        return $this->belongsToMany(Movilidad::class, 'reserva_movilidads', 'id_reserva', 'id_movilidad')
            ->with('guias'); // Carga también los guías
    }

    // Relación con los pagos (una reserva puede tener varios pagos)
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'id_reserva', 'id_reserva');
    }
}
