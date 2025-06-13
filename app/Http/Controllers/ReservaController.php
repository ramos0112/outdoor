<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\Ruta;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:reservas.gestionar')->only(['index', 'show']);
        $this->middleware('can:reservas.crear')->only(['create', 'store']);
        $this->middleware('can:reservas.editar')->only(['edit', 'update']);
        $this->middleware('can:reservas.eliminar')->only(['destroy']);
    }
    public function index()
    {
        // Obtener las últimas 5 reservas actualizadas
        $reservas = Reserva::with(['fechaDisponible.ruta', 'clientes', 'movilidads.guias', 'pagos'])
            ->orderBy('updated_at', 'desc') // Ordenar por la fecha de la última actualización
            ->take(5) // Limitar a las 5 más recientes
            ->get();

        return view('reservas.index', compact('reservas'));
    }
    

    public function buscarPorDNI(Request $request)
    {
        $dni = $request->input('dni');
            $fecha = $request->input('fecha', Carbon::today()->toDateString()); // Fecha por defecto: hoy


        $reserva = Reserva::with(['fechaDisponible.ruta', 'clientes', 'movilidads.guias', 'pagos'])
            ->whereHas('clientes', function ($q) use ($dni) {
                $q->where('numero_documento', $dni);
            })
            ->whereHas('fechaDisponible', function ($q) use ($fecha) {
            $q->whereDate('fecha', $fecha); // Filtrar por fecha disponible
        })
            ->latest()
            ->first();

        if (!$reserva) {
            return response()->json(['error' => 'No se encontró una reserva para este DNI.'], 404);
        }

        return response()->json($reserva);
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
    }

    /**
     * Display the specified resource.
     */
    public function show(Reserva $reserva)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reserva $reserva)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reserva $reserva)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reserva $reserva)
    {
        //
    }
}
