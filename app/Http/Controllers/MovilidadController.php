<?php

namespace App\Http\Controllers;

use App\Models\Movilidad;
use App\Models\Guia;
use Illuminate\Http\Request;

class MovilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $movilidades = Movilidad::all();
        $guias = Guia::all();
        return view('movilidades.index', compact('movilidades', 'guias'));
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
        // Validar los datos del formulario
        $request->validate([
            'capacidad' => 'required|integer',
            'estado' => 'required|in:Disponible,Ocupado',
            'guias' => 'required|array', // Asegurarse de que se seleccionen guías
            'guias.*' => 'exists:guias,id_guia' // Verifica que los guías seleccionados existan
        ]);

        // Crear la nueva movilidad
        $movilidad = Movilidad::create([
            'capacidad' => $request->capacidad,
            'estado' => $request->estado
        ]);

        // Asignar guías a la movilidad
        //$movilidad->guias()->sync($request->guias); // Esto es para asignar los guías seleccionados
        $movilidad->guias()->sync($request->id_guia);

        return redirect()->route('movilidades.index')->with('success', 'Movilidad creada y guías asignados correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Movilidad $movilidad)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movilidad $movilidad)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'capacidad' => 'required|integer',
            'estado' => 'required|in:Disponible,Ocupado',
            'guias' => 'required|array',
            'guias.*' => 'exists:guias,id_guia',
        ]);
    
        // Obtener la movilidad y actualizarla
        $movilidad = Movilidad::findOrFail($id);
        $movilidad->update([
            'capacidad' => $request->capacidad,
            'estado' => $request->estado,
        ]);
    
        // Sincronizar los guías seleccionados con la movilidad
        $movilidad->guias()->sync($request->guias);
    
        return redirect()->route('movilidades.index')->with('success', 'Movilidad actualizada correctamente');
    }
    



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movilidad $movilidad)
    {
        //
        $movilidad->delete();    
        return redirect()->route('movilidades.index')->with('success', 'Movilidad eliminada correctamente');
    }
}
