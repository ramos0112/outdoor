<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class homeController extends Controller
{
    //
    public function index(){
        return view('paguinas.rutas');
    }

    public function mostrarRutasWeb()
    {
        $rutas = Ruta::with('imagenes')->get(); // Relación definida como imagenes()
        return view('paguinas.rutas', compact('rutas'));
    }
    public function trekking()
    {
        $rutas = Ruta::with('imagenes')->get(); // Relación definida como imagenes()
        return view('paguinas.trekking', compact('rutas'));
    }

        public function blog()
    {
        $rutas = Ruta::with('imagenes')->get(); // Relación definida como imagenes()
        return view('paguinas.blog', compact('rutas'));
    }

}
