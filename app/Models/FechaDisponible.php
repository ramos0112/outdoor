<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FechaDisponible extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'id_fecha';
    protected $table = 'fecha_disponibles';

    protected $fillable = [
        'id_ruta',
        'fecha',
    ];

    // Relación con el modelo Ruta (en singular, ya que es 'belongsTo')
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }
}
