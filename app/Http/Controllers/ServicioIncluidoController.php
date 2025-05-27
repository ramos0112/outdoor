<?php

namespace App\Http\Controllers;

use App\Models\ServicioIncluido;
use App\Models\Ruta;
use Illuminate\Http\Request;

class ServicioIncluidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $servicios = ServicioIncluido::with('ruta')->get();
        $rutas = Ruta::all();
        return view('serviciosincluidos.index', compact('servicios', 'rutas'));
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
        //
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'servicio' => 'required|string|max:255',
        ]);

        ServicioIncluido::create([
            'id_ruta' => $request->id_ruta,
            'servicio' => $request->servicio,
        ]);

        return redirect()->route('servicios.index')->with('success', 'Servicio creado exitosamente');

        
    }

    /**
     * Display the specified resource.
     */
    public function show(ServicioIncluido $servicioIncluido)
    {
        //
        
    }

    /**
     * Show the form for editing the specified resource.
     *    public function edit(ServicioIncluido $servicioIncluido)
     */
    public function edit($id)
    {
        //
        $servicio = ServicioIncluido::findOrFail($id);
        $rutas = Ruta::all();
        return view('serviciosincluidos.edit', compact('servicio', 'rutas'));
    }

    /**
     * Update the specified resource in storage.
     */
    /** 
    *public function update(Request $request, ServicioIncluido $servicioIncluido)
    */

    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'servicio' => 'required|string|max:255',
        ]);

        $servicio = ServicioIncluido::findOrFail($id);
        $servicio->update([
            'id_ruta' => $request->id_ruta,
            'servicio' => $request->servicio,
        ]);

        return redirect()->route('servicios.index')->with('success', 'Servicio actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ServicioIncluido $servicioIncluido)
    {
        //
    }
}
