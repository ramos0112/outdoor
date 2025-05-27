<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovilidadGuia extends Model
{
    use HasFactory;

    protected $table = 'movilidad_guias';

    public $timestamps = false;

    protected $primaryKey = ['id_movilidad', 'id_guia'];
    

    protected $fillable = [
        'id_movilidad',
        'id_guia',
    ];
}

