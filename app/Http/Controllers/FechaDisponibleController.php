<?php

namespace App\Http\Controllers;

use App\Models\FechaDisponible;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Whoops\Run;

class FechaDisponibleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $fechas = FechaDisponible::with('ruta')->get();

        $rutas = Ruta::all( );
        return view('fechasdisponible.index', compact('fechas', 'rutas'));
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
            'fecha' => 'required|date',
        ]);

        
        $fechas = FechaDisponible::create([
            'id_ruta' => $request->id_ruta,
            'fecha' => $request->fecha,
        ]);

        return redirect()->route('fechas.index')->with('success', 'Fecha disponible creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(FechaDisponible $fechaDisponible)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FechaDisponible $fechaDisponible)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FechaDisponible $fecha)
    {
        //
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'fecha' => 'required|date',
        ]);

        $fecha->update([
            'id_ruta' => $request->id_ruta,
            'fecha' => $request->fecha,
        ]);

        return redirect()->route('fechas.index')->with('success', 'Fecha disponible actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FechaDisponible $fechaDisponible)
    {
        
    }
}
