<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Reserva;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:pagos.ver')->only(['index', 'show']);
        $this->middleware('can:pagos.crear')->only(['create', 'store']);
        $this->middleware('can:pagos.editar')->only(['edit', 'update']);
        $this->middleware('can:pagos.eliminar')->only(['destroy']);
    }
    public function index()
    {
        //
        // Obtener todos los pagos, puedes hacer paginación si lo necesitas
        $pagos = Pago::all();

        // Retornar la vista con los pagos
        return view('pagos.index', compact('pagos'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_reserva' => 'required|exists:reservas,id_reserva',
            'metodo_pago' => 'required|string|max:50',
            'monto_pagado' => 'required|numeric|min:0.01',
        ]);

        $reserva = Reserva::findOrFail($request->id_reserva);

        // Calcular suma actual de pagos
        $totalPagado = $reserva->pagos()->sum('monto_pagado');
        $saldoRestante = $reserva->precio_total - $totalPagado;

        // Verificar que el nuevo pago no exceda el saldo
        if ($request->monto_pagado > $saldoRestante) {
            return response()->json([
                'success' => false,
                'message' => 'El monto pagado excede el saldo restante. No se permite que el saldo sea negativo.'
            ], 422);
        }

        // Registrar el nuevo pago
        Pago::create([
            'id_reserva' => $request->id_reserva,
            'metodo_pago' => $request->metodo_pago,
            'monto_pagado' => $request->monto_pagado,
            'fecha_pago' => now(),
        ]);

        // Recalcular el total pagado y actualizar la reserva
        $totalPagado += $request->monto_pagado;
        $nuevoSaldo = $reserva->precio_total - $totalPagado;

        $reserva->saldo = $nuevoSaldo;
        $reserva->estado = $nuevoSaldo <= 0 ? 'Pagado' : 'Pendiente';
        $reserva->save();

        return response()->json([
            'success' => true,
            'reserva' => $reserva->load(['fechaDisponible.ruta', 'clientes', 'movilidads.guias']),
        ]);
    }

    public function show(Pago $pago)
    {
        //
    }

    public function edit(Pago $pago)
    {
        //
    }

    public function update(Request $request, Pago $pago)
    {
        //
    }

    public function destroy(Pago $pago)
    {
        //
    }
}
