<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LugarVisitar extends Model
{
    use HasFactory;
    protected $primaryKey = 'id_lugar';
    protected $table = 'lugar_visitars';

    protected $fillable = [
        'id_ruta',
        'nombre_lugar',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta');
    }
}

