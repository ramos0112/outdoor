<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class homeController extends Controller
{

    public function index(){
        return view('paguinas.rutas');
    }

    public function home()
{
    $rutas = Ruta::with('imagenes')->get();
    $rutasTrekking = Ruta::with('imagenes')->where('tipo', 'Trekking')->get();
    $rutasAventura = Ruta::with('imagenes')->where('tipo', 'Aventura')->get();

    return view('paguinas.home', compact('rutasTrekking', 'rutasAventura', 'rutas'));
}

public function rutasPorTipo($tipo)
{
    $rutas = Ruta::with('imagenes')
        ->whereRaw('LOWER(tipo) = ?', [strtolower($tipo)])
        ->get();

    return view('paguinas.rutas', compact('rutas', 'tipo'));
}


        public function blog()
    {
        $rutas = Ruta::with('imagenes')->get(); // Relación definida como imagenes()
        return view('paguinas.blog', compact('rutas'));
    }

    public function mostrarDescripcion($id_ruta)
    {
        $ruta = Ruta::with([
            'detalles',
            'lugaresVisitar',
            'serviciosIncluidos',
            'imagenes',
            'fechasDisponibles' => function ($query) {
                $query->where('fecha', '>=', now())->orderBy('fecha')->limit(6);
            }
        ])->findOrFail($id_ruta);
    
        $rutas = Ruta::with('imagenes')
            ->where('id_ruta', '!=', $id_ruta)
            ->get();
    
        return view('paguinas.descripcionruta', compact('ruta', 'rutas'));
    }
    

}
