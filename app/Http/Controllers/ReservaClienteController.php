<?php

namespace App\Http\Controllers;

use App\Models\Ruta;
use App\Models\Reserva;
use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Models\ReservaCliente;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservaClienteController extends Controller
{

    public function formulario($id_ruta)
    {
        $ruta = Ruta::with('fechasDisponibles')->findOrFail($id_ruta);
        return view('paguinas.formularioreserva', compact('ruta'));
    }

    public function store(Request $request)
    {
        /*
        dd($request->all());
        DB::beginTransaction();

        try {
            // Crear cliente principal
            $cliente = Cliente::create([
                'tipo_documento'    => $request->input('tipo_documento'),
                'numero_documento'  => $request->input('numero_documento'),
                'nombre'            => $request->input('nombre'),
                'apellido'          => $request->input('apellido'),
                'fecha_nacimiento'  => $request->input('fecha_nacimiento'),
                'email'             => $request->input('email'),
                'telefono'          => $request->input('telefono'),
                'pais'              => $request->input('pais'),
                'region'            => $request->input('region'),
                'ciudad'            => $request->input('ciudad'),
            ]);

            // Obtener ruta y fecha
            $ruta = Ruta::findOrFail($request->ruta_id);
            $fecha_id = $request->input('id_fecha');
            $cantidad_personas = $request->input('cantidad_personas', 1);

            $precio_total = $cantidad_personas * $ruta->precio_actual;
            $monto_pagado = $request->input('monto_pagado', $precio_total); // Simulación
            $saldo = $precio_total - $monto_pagado;

            // Crear reserva
            $reserva = Reserva::create([
                'id_fecha'          => $fecha_id,
                'fecha_reserva'     => Carbon::now(),
                'cantidad_personas' => $cantidad_personas,
                'precio_total'      => $precio_total,
                'saldo'             => $saldo,
                'estado'            => 'pendiente', // puedes cambiar a 'pagado' según lógica de negocio
            ]);
            //dd($reserva->id_reserva);
            // Asociar cliente principal a la reserva
            ReservaCliente::create([
                'id_reserva' => $reserva->id_reserva,
                'id_cliente' => $cliente->id_cliente,
            ]);


            if ($request->filled('acompanantes')) {
                $acompanantes = json_decode($request->input('acompanantes'), true);

                foreach ($acompanantes as $acompanante) {
                    // Crear cliente acompañante
                    $nuevoAcompanante = Cliente::create([
                        'tipo_documento'    => $acompanante['tipo'],
                        'numero_documento'  => $acompanante['doc'],
                        'nombre'            => $acompanante['nombre'],
                        'apellido'          => $acompanante['apellido'],
                        'fecha_nacimiento'  => $acompanante['fecha'],
                        'telefono'             => $acompanante['telefono'],
                    ]);

                    // Asociar al cliente acompañante con la reserva
                    ReservaCliente::create([
                        'id_reserva' => $reserva->id_reserva,
                        'id_cliente' => $nuevoAcompanante->id_cliente,
                    ]);
                }
            }
            // Simulación de pago (esto vendría de una integración con MercadoPago)
            Pago::create([
                'id_reserva'   => $reserva->id_reserva,
                'metodo_pago'  => $request->input('metodo_pago', 'mercadopago'),
                'monto_pagado' => $monto_pagado,
                'fecha_pago'   => Carbon::now(),
            ]);

            DB::commit();

            return back()->with('success', 'Reserva realizada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al procesar la reserva: ' . $e->getMessage()]);
        }
            */
    }
}
