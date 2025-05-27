<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $primaryKey = 'id_cliente';

    protected $fillable = [
        'nombre',
        'apellido',
        'tipo_documento',
        'numero_documento',
        'fecha_nacimiento',
        'email',
        'telefono',
        'pais',
        'region',
        'ciudad',
    ];
    
    public function reservas()
    {
        return $this->belongsToMany(Reserva::class, 'reserva_clientes','id_cliente','id_reserva');
    }
}
