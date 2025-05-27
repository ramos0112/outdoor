<?php

namespace App\Http\Controllers;

use App\Models\LugarVisitar;
use App\Models\Ruta;
use Illuminate\Http\Request;

class LugarVisitarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $lugares = LugarVisitar::with('ruta')->get();
        $rutas = Ruta::all();
        return view('lugaresvisitar.index', compact('lugares', 'rutas'));
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
            'nombre_lugar' => 'required|string|max:255',
        ]);

        $lugares = LugarVisitar::create([
            'id_ruta' => $request->id_ruta,
            'nombre_lugar' => $request->nombre_lugar,
        ]);

        return redirect()->route('lugares.index')->with('success', 'Lugar creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(LugarVisitar $lugarVisitar)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $lugar = LugarVisitar::findOrFail($id); // Buscar el lugar por su ID
        $rutas = Ruta::all(); // Obtener todas las rutas disponibles
        return view('lugaresvisitar.edit', compact('lugar', 'rutas')); // Retornar la vista con el lugar y las rutas
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'nombre_lugar' => 'required|string|max:255',
        ]);

        // Buscar el lugar y actualizarlo
        $lugar = LugarVisitar::findOrFail($id);
        $lugar->update([
            'id_ruta' => $request->id_ruta,
            'nombre_lugar' => $request->nombre_lugar,
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('lugares.index')->with('success', 'Lugar actualizado exitosamente');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LugarVisitar $lugarVisitar)
    {
        //
    }
}
