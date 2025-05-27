<?php

namespace App\Http\Controllers;

use App\Models\Guia;
use Illuminate\Http\Request;

class GuiaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $guias = Guia::all();
        return view('guias.index', compact('guias'));
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
        // Validar los datos
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:guias',
        ]);

        // Crear nueva guía
        Guia::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'email' => $request->email,
        ]);

        return redirect()->route('guias.index')->with('success', 'Guía creada con éxito');
    }

    /**
     * Display the specified resource.
     */
    public function show(Guia $guia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    // Mostrar el formulario para editar una guía
    public function edit($id)
    {
        $guia = Guia::findOrFail($id);
        return view('guias.edit', compact('guia'));
    }

    // Actualizar la guía en la base de datos
    public function update(Request $request, $id)
    {
        // Validación
        $request->validate([
            'nombre' => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'email' => 'required|email|unique:guias,email,' . $id . ',id_guia', // Aquí corregimos la validación
        ]);

        // Obtener la guía existente y actualizarla
        $guia = Guia::findOrFail($id);
        $guia->nombre = $request->nombre;
        $guia->apellido = $request->apellido;
        $guia->telefono = $request->telefono;
        $guia->email = $request->email;
        $guia->save();

        // Redirigir de vuelta con un mensaje de éxito
        return redirect()->route('guias.index')->with('success', 'Guía actualizada con éxito');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Encontrar el guía por su ID
        $guia = Guia::findOrFail($id);
    
        // Eliminar el guía
        $guia->delete();
    
        // Redirigir con mensaje de éxito
        return redirect()->route('guias.index')->with('success', 'Guía eliminada con éxito');
    }
    
}
