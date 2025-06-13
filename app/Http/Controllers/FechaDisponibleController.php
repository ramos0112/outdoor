<?php

namespace App\Http\Controllers;

use App\Models\FechaDisponible;
use App\Models\Ruta;
use Illuminate\Http\Request;

class FechaDisponibleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:fechas.ver')->only(['index', 'show']);
        $this->middleware('can:fechas.crear')->only(['create', 'store']);
        $this->middleware('can:fechas.editar')->only(['edit', 'update']);
        $this->middleware('can:fechas.eliminar')->only(['destroy']);
    }

    public function index()
    {
        $fechas = FechaDisponible::with('ruta')->get();
        $rutas = Ruta::all();
        return view('fechasdisponible.index', compact('fechas', 'rutas'));
    }

    public function create()
    {
        //
    }
    public function store(Request $request)
    {
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

    public function show(FechaDisponible $fechaDisponible)
    {
        //
    }

    public function edit(FechaDisponible $fechaDisponible)
    {
        //
    }

    public function update(Request $request, FechaDisponible $fecha)
    {
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

    public function destroy(FechaDisponible $fechaDisponible) {}
}
