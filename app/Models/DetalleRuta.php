<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRuta extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_detalle';
    protected $table = 'detalle_rutas';

    protected $fillable = [
        'id_ruta',
        'descripcion',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }
}

