<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Reserva;
use App\Models\Pago;
use App\Models\Ruta;
use App\Models\ReservaCliente;
use App\Models\Movilidad;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\FechaDisponible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmacionReserva;

class ListarReservasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:reservas.ver')->only(['index', 'show']);
        $this->middleware('can:reservas.crear')->only(['store']);
        $this->middleware('can:reservas.editar')->only(['edit', 'update']);
        $this->middleware('can:reservas.eliminar')->only(['destroy']);
    }

    public function index()
    {
        // Obtener todas las rutas
        $rutas = Ruta::all();

        // Obtener todas las movilidades con capacidad disponible
        $movilidades = Movilidad::where('estado', 'Disponible')->where('capacidad', '>', 0)->get();

        // Obtener todas las reservas con sus relaciones
        $listareservas = Reserva::with(['fechaDisponible.ruta', 'clientes', 'movilidads.guias'])->get();

        // Pasar las reservas, rutas y movilidades a la vista
        return view('listareservas.index', compact('listareservas', 'rutas', 'movilidades'));
    }


    public function obtenerFechasPorRuta($id)
    {
        $fechas = FechaDisponible::where('id_ruta', $id)
            ->orderBy('fecha', 'asc')
            ->get(['id_fecha', 'fecha']);

        return response()->json($fechas);
    }
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Validación de datos
            $request->validate([
                'id_ruta' => 'required|exists:rutas,id_ruta',
                'id_fecha' => 'required|exists:fecha_disponibles,id_fecha',
                'id_movilidad' => 'required|exists:movilidads,id_movilidad', // Agregar validación para la movilidad
                'tipo_documento' => 'required|string',
                'numero_documento' => 'required|string',
                'nombre' => 'required|string',
                'apellido' => 'required|string',
                'email' => 'nullable|email',
                'telefono' => 'nullable|string',
                'pais' => 'nullable|string',
                'region' => 'nullable|string',
                'ciudad' => 'nullable|string',
                'fecha_nacimiento' => 'nullable|date',
                'cantidad_personas' => 'required|integer|min:1',
                'metodo_pago' => 'required|string',
                'monto_pagado' => 'required|numeric|min:0',
            ]);

            // 1. Buscar o crear cliente
            $cliente = Cliente::updateOrCreate(
                ['numero_documento' => $request->numero_documento],
                [
                    'tipo_documento'   => $request->tipo_documento,
                    'nombre'           => $request->nombre,
                    'apellido'         => $request->apellido,
                    'fecha_nacimiento' => $request->fecha_nacimiento,
                    'email'            => $request->email,
                    'telefono'         => $request->telefono,
                    'pais'             => $request->pais,
                    'region'           => $request->region,
                    'ciudad'           => $request->ciudad,
                ]
            );

            // 2. Obtener precio actual de la ruta
            $ruta = Ruta::findOrFail($request->id_ruta);
            $precioActual = $ruta->precio_actual;

            // 3. Calcular precios
            $cantidadPersonas = $request->cantidad_personas;
            $precioTotal = $cantidadPersonas * $precioActual;
            $montoPagado = $request->monto_pagado;
            $saldo = $precioTotal - $montoPagado;

            // 4. Crear la reserva
            $reserva = Reserva::create([
                'id_fecha'         => $request->id_fecha,
                'fecha_reserva'    => Carbon::now(),
                'cantidad_personas' => $cantidadPersonas,
                'precio_total'     => $precioTotal,
                'saldo'            => $saldo,
                'estado'           => 'pendiente',
            ]);

            // 5. Relacionar cliente con reserva
            $reserva->clientes()->attach($cliente->id_cliente);

            // 6. Registrar el pago inicial
            Pago::create([
                'id_reserva'  => $reserva->id_reserva,
                'metodo_pago' => $request->metodo_pago,
                'monto_pagado' => $montoPagado,
                'fecha_pago'  => Carbon::now(),
            ]);

            // 7. Asociar la movilidad a la reserva
            $movilidadId = $request->id_movilidad; // Obtener la movilidad seleccionada
            $movilidad = Movilidad::findOrFail($movilidadId); // Obtener la movilidad
            $movilidadCapacidadRestante = $movilidad->capacidad;

            // Calcular el total de personas (incluyendo acompañantes)
            $acompanantes = json_decode($request->acompanantes, true);
            $totalPersonasReserva = $cantidadPersonas + count($acompanantes);

            // Verificar si la movilidad tiene suficiente capacidad
            if ($movilidadCapacidadRestante < $totalPersonasReserva) {
                DB::rollBack();
                return back()->with('error', 'La capacidad de la movilidad no es suficiente para la reserva.');
            }

            // Relacionar la reserva con la movilidad
            $reserva->movilidads()->attach($movilidadId);

            // Actualizar la capacidad de la movilidad
            $movilidad->capacidad -= $totalPersonasReserva;

            // Si la capacidad es cero o negativa, marcar la movilidad como "Ocupado"
            if ($movilidad->capacidad <= 0) {
                $movilidad->estado = 'Ocupado';
                $movilidad->capacidad = 0; // Aseguramos que la capacidad no sea negativa
            } else {
                $movilidad->estado = 'Disponible';
            }
            $movilidad->save();

            // Procesar acompañantes
            if ($acompanantes && is_array($acompanantes)) {
                foreach ($acompanantes as $acomp) {
                    $clienteAcompanante = Cliente::updateOrCreate(
                        ['numero_documento' => $acomp['doc']], // Condición de búsqueda
                        [
                            'tipo_documento' => $acomp['tipo'],
                            'nombre' => $acomp['nombre'],
                            'apellido' => $acomp['apellido'],
                            'fecha_nacimiento' => $acomp['fecha'],
                            'email' => $acomp['email'],
                        ]
                    );

                    // Registrar la relación en la tabla reserva_clientes
                    ReservaCliente::create([
                        'id_reserva' => $reserva->id_reserva,
                        'id_cliente' => $clienteAcompanante->id_cliente
                    ]);
                }
            }

            // Confirmación de la reserva por correo
            DB::commit();
            Mail::to($cliente->email)->send(new ConfirmacionReserva(
                $cliente,
                $reserva,
                $ruta,
                $reserva->fechaDisponible
            ));

            return redirect()->route('listareservas.index')->with('success', 'Reserva registrada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al guardar la reserva: ' . $e->getMessage());
        }
    }

    public function buscarPorDocumento($numero_documento)
    {
        $cliente = Cliente::where('numero_documento', $numero_documento)->first();

        if ($cliente) {
            return response()->json($cliente);
        } else {
            return response()->json(null);
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
