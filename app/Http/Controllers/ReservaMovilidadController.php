<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Movilidad;
use Illuminate\Support\Facades\DB;


class ReservaMovilidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:reservas.movilidad')->only(['index']);
        $this->middleware('can:reservas.asignar')->only(['store']);
    }


    public function index()
    {
        $reservas = Reserva::whereDoesntHave('movilidads')->with('fechaDisponible.ruta')->get();
        $movilidades = Movilidad::where('estado', 'Disponible')->get();

        return view('reservasmovilidad.index', compact('reservas', 'movilidades'));
    }

    public function store(Request $request)
    {
        $movilidad = Movilidad::findOrFail($request->id_movilidad);
        $reservasIds = $request->reservas ?? [];

        $reservas = Reserva::whereIn('id_reserva', $reservasIds)->get();
        $totalPersonas = $reservas->sum('cantidad_personas');

        if ($movilidad->capacidad < $totalPersonas) {
            return redirect()->back()->withErrors(['La capacidad de la movilidad no es suficiente...']);
        }

        // Asignar reservas
        foreach ($reservas as $reserva) {
            DB::table('reserva_movilidads')->insert([
                'id_reserva' => $reserva->id_reserva,
                'id_movilidad' => $movilidad->id_movilidad,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Actualizar capacidad y estado
        $movilidad->capacidad -= $totalPersonas;
        $movilidad->estado = $movilidad->capacidad <= 0 ? 'Ocupado' : 'Disponible';
        $movilidad->save();

        return redirect()->back()->with('success', 'Reservas asignadas correctamente.');
    }
}
