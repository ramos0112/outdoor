<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guia extends Model
{
    use HasFactory;

    protected $table = 'guias';

    protected $primaryKey = 'id_guia';

    protected $fillable = [
        'nombre',
        'apellido',
        'telefono',
        'email',
    ];

    public function movilidads()
    {
        return $this->belongsToMany(Movilidad::class, 'movilidad_guias', 'id_guia', 'id_movilidad');
    }
}

