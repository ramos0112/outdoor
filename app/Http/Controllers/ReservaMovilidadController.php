<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserva;
use App\Models\Movilidad;
use App\Services\ReservaMovilidadDataTableService;
use Illuminate\Support\Facades\DB;

class ReservaMovilidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:reservas.movilidad')->only(['index']);
        $this->middleware('can:reservas.asignar')->only(['store']);
    }

    public function index(Request $request, ReservaMovilidadDataTableService $dataTableService)
    {
        if ($request->ajax()) {
            return response()->json($dataTableService->procesar($request));
        }

        // Las movilidades siguen siendo pocas y fijas, se cargan normalmente
        $movilidades = Movilidad::where('estado', 'Disponible')->get();

        return view('reservasmovilidad.index', compact('movilidades'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_movilidad' => 'required|exists:movilidads,id_movilidad',
            'reservas' => 'required|array|min:1',
        ], [
            'reservas.required' => 'Debe seleccionar al menos una reserva para asignar.'
        ]);

        $movilidad = Movilidad::findOrFail($request->id_movilidad);
        $reservasIds = $request->reservas;

        $reservas = Reserva::whereIn('id_reserva', $reservasIds)->get();
        $totalPersonas = $reservas->sum('cantidad_personas');

        if ($movilidad->capacidad < $totalPersonas) {
            return redirect()->back()->withErrors(['La capacidad de la movilidad no es suficiente para la cantidad de personas seleccionadas.']);
        }

        // Ejecutar transacción segura en BD
        DB::transaction(function () use ($reservasIds, $movilidad, $totalPersonas) {
            $insertData = [];
            foreach ($reservasIds as $id) {
                $insertData[] = [
                    'id_reserva' => $id,
                    'id_movilidad' => $movilidad->id_movilidad,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            // Inserción masiva optimizada
            DB::table('reserva_movilidads')->insert($insertData);

            // Actualizar capacidad y estado de la movilidad
            $movilidad->capacidad -= $totalPersonas;
            $movilidad->estado = $movilidad->capacidad <= 0 ? 'Ocupado' : 'Disponible';
            $movilidad->save();
        });

        return redirect()->route('reservasmovilidad.index')->with('success', 'Reservas asignadas correctamente.');
    }
}