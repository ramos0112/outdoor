<?php

namespace App\Http\Controllers;

use App\Models\DetalleRuta;
use App\Models\Ruta;
use App\Models\FechaDisponible;

use Illuminate\Http\Request;


class DetalleRutaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Obtener todos los detalles de ruta con las rutas asociadas
        $detallerutas = DetalleRuta::with('ruta')->get();
        $rutas = Ruta::all();
        return view('detalleruta.index', compact('detallerutas', 'rutas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'descripcion' => 'required|string|max:255',
        ]);

        // Crear el detalle de ruta
        $detallerutas = DetalleRuta::create([
            'id_ruta' => $request->id_ruta,
            'descripcion' => $request->descripcion,
        ]);

        return redirect()->route('detalleruta.index')->with('success', 'Detalle de Ruta creado exitosamente');
    }
    /**
     * Display the specified resource.
     */
    public function show(DetalleRuta $detalleruta)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {}

    public function update(Request $request, $id_detalle)
    {
        // Obtener el detalle de ruta a actualizar
        $detallerutas = DetalleRuta::findOrFail($id_detalle);

        // Actualizar el detalle de ruta
        $detallerutas->update([
            'descripcion' => $request->descripcion,
            'id_ruta' => $request->id_ruta,
        ]);

        return redirect()->route('detalleruta.index')->with('success', 'Detalle de Ruta actualizado exitosamente');
    }
    
    /**
     * * Remove the specified resource from storage.
     *  */

    public function destroy($id)
    {
        // Eliminar el detalle de ruta
        $detallerutas = DetalleRuta::findOrFail($id);
        $detallerutas->delete();

        return redirect()->route('detalleruta.index')->with('success', 'Detalle de Ruta eliminado exitosamente');
    }
}
