<?php

namespace App\Http\Controllers;

use App\Models\ServicioIncluido;
use App\Models\Ruta;
use Illuminate\Http\Request;

class ServicioIncluidoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:servicios.ver')->only(['index', 'show']);
        $this->middleware('can:servicios.crear')->only(['store']);
        $this->middleware('can:servicios.editar')->only(['update']);
        $this->middleware('can:servicios.eliminar')->only(['destroy']);
    }

    public function index()
    {
        //
        $servicios = ServicioIncluido::with('ruta')->get();
        $rutas = Ruta::all();
        return view('serviciosincluidos.index', compact('servicios', 'rutas'));
    }

    public function create()
    {
        //
    }

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

    public function show(ServicioIncluido $servicioIncluido)
    {
        //

    }

    public function edit($id)
    {
        //
        $servicio = ServicioIncluido::findOrFail($id);
        $rutas = Ruta::all();
        return view('serviciosincluidos.edit', compact('servicio', 'rutas'));
    }

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
