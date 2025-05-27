<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Imagen extends Model
{
    use HasFactory;

    protected $table = 'imagens';

    protected $primaryKey = 'id_imagen';

    public $timestamps = false;

    protected $fillable = [
        'id_ruta',
        'url_imagen',
    ];

    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'id_ruta', 'id_ruta');
    }

    public function getUrlImagenAttribute($value)
    {
        return asset($value);
    }

}
