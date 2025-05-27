<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicioIncluido extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_servicio';
    protected $table = 'servicio_incluidos';

    protected $fillable = [
        'id_ruta',
        'servicio',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }
}
