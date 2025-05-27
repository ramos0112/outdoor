<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory;

    protected $table = 'rutas';
    protected $primaryKey = 'id_ruta'; 
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'nombre_ruta',
        'descripcion_general',
        'tipo',
        'precio_regular',
        'descuento',
        'precio_actual',
        'dificultad',
        'estado',
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleRuta::class, 'id_ruta');
    }

    public function fechasDisponibles()
    {
        return $this->hasMany(FechaDisponible::class, 'id_ruta');
    }

    public function lugaresVisitar()
    {
        return $this->hasMany(LugarVisitar::class, 'id_ruta');
    }

    public function serviciosIncluidos()
    {
        return $this->hasMany(ServicioIncluido::class, 'id_ruta');
    }

    public function imagenes()
    {
        return $this->hasMany(Imagen::class, 'id_ruta');
    }
}
