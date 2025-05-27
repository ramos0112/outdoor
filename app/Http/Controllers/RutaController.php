<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $rutas = Ruta::all();
        return view('Rutas.index', compact('rutas'));
    }

    public function home()
    {
        $rutas = Ruta::with('imagenes')->get();
        return view('paguinas.home', compact('rutas'));
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
    

    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'nombre_ruta' => 'required|string|max:255',
            'descripcion_general' => 'nullable|string',
            'tipo' => 'nullable|string',
            'precio_regular' => 'nullable|numeric',
            'descuento' => 'nullable|numeric',
            'precio_actual' => 'nullable|numeric',
            'dificultad' => 'nullable|string',
            'estado' => 'nullable|string',
        ]);

        // Crear una nueva ruta
        Ruta::create([
            'nombre_ruta' => $request->nombre_ruta,
            'descripcion_general' => $request->descripcion_general,
            'tipo' => $request->tipo,
            'precio_regular' => $request->precio_regular,
            'descuento' => $request->descuento,
            'precio_actual' => $request->precio_actual,
            'dificultad' => $request->dificultad,
            'estado' => $request->estado,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('rutas.index')->with('success', 'Ruta creada exitosamente.');
    }

    public function show($id)
    {
        $ruta = Ruta::findOrFail($id);
        return view('rutas.show', compact('ruta'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre_ruta' => 'required|string|max:255',
            'descripcion_general' => 'nullable|string',
            'tipo' => 'nullable|string',
            'precio_regular' => 'nullable|numeric',
            'descuento' => 'nullable|numeric',
            'precio_actual' => 'nullable|numeric',
            'dificultad' => 'nullable|string',
            'estado' => 'nullable|string',
        ]);

        $ruta = Ruta::findOrFail($id);
        $ruta->update($request->all());

        return redirect()->route('rutas.index')->with('success', 'Ruta actualizada correctamente.');
    }

    public function destroy($id)
    {
        $ruta = Ruta::findOrFail($id);
        $ruta->delete();

        return redirect()->route('rutas.index')->with('success', 'Ruta eliminada correctamente.');
    }
}
