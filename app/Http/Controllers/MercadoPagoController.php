<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;
use App\Models\Ruta;
use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\ReservaCliente;
use App\Models\Pago;
use Carbon\Carbon;
use Illuminate\Support\Str;
use MercadoPago\Client\Payment\PaymentClient; // Aquí cambiamos
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmacionReserva;

class MercadoPagoController extends Controller
{
    public function checkout(Request $request)
    {
        // 1. Configurar el access token
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        // 2. Registrar datos de entrada para depuración
        Log::info('Datos recibidos en checkout:', $request->all());

        // 3. Calcular precio total
        $cantidad = (int) $request->input('cantidad_personas', 1);
        $precio = (float) $request->input('precio_actual', 0);
        $total = round($cantidad * $precio, 2);
        $montopagar = round($total * 0.5, 2);

        // 4. Crear array de ítems para la preferencia
        $items = [[
            "title" => $request->input('nombre_ruta', 'Reserva'),
            "quantity" => 1,
            "unit_price" => $montopagar,
            "currency_id" => "PEN",
        ]];

        // 5. Información del cliente
        $payer = [
            "name" => $request->input('nombre'),
            "surname" => $request->input('apellido'),
            "email" => $request->input('email'),
        ];

        //6. URLs de redireccionamiento PARA PRODUCCION
        $baseUrl = config('app.url');

        $backUrls = [
            "success" => "$baseUrl/mercadopago/success",
            "failure" => "$baseUrl/mercadopago/failure",
        ];

        // 7. Crear preferencia de pago
        $preferenceData = [
            "items" => $items,
            "payer" => $payer,
            "back_urls" => $backUrls,
            "auto_return" => "approved",
            //"external_reference" => "reserva_" . time(),  // Usando el tiempo como ID único
            "external_reference" => 'reserva_' . Str::uuid(),

        ];

        // 8. Enviar a Mercado Pago y Crear preferencia
        try {
            $client = new PreferenceClient();
            $preference = $client->create($preferenceData);

            Log::info('Preferencia llamada:', (array) $preference);
            session(['datos_reserva' => $request->all()]);
            session()->save(); // ✅ Fuerza guardar antes de redirigir

            // Redirigir al checkout
            return redirect()->away($preference->init_point);
        } catch (MPApiException $e) {
            Log::error('Error al crear preferencia: ' . $e->getMessage(), [
                'response' => $e->getApiResponse()->getContent()
            ]);
            return back()->with('error', 'No se pudo iniciar el pago. Intenta nuevamente.');
        } catch (\Exception $e) {
            Log::error('Error general: ' . $e->getMessage());
            return back()->with('error', 'Ocurrió un error inesperado.');
        }
    }

    // Redirección cuando el pago es exitoso
    public function success(Request $request)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.access_token'));

        try {
            Log::info('Redireccionado a SUCCESS:', $request->all());

            $paymentId = $request->input('payment_id');
            $paymentClient = new PaymentClient();
            $payment = $paymentClient->get($paymentId);

            Log::info('Estado del pago:', ['status' => $payment->status]);

            if ($payment->status === 'approved') {
                $montoPagado = $payment->transaction_amount;
                $fechaPago = Carbon::parse($payment->date_approved);
                $metodoPago = $payment->payment_type_id;
                $externalReference = $request->input('external_reference');

                // ✅ Recuperar los datos de la reserva desde la sesión
                $datosReserva = session('datos_reserva');
                if (!$datosReserva) {
                    Log::error('No hay datos en la sesión para crear la reserva.');
                    return view('mercadopago.fallo2', [
                        'error' => 'No se encontraron los datos de la reserva. Intenta nuevamente.',
                    ]);
                }

                $idRuta = $datosReserva['id_ruta'];
                $idFecha = $datosReserva['id_fecha'];
                $cantidadPersonas = $datosReserva['cantidad_personas'];

                $ruta = Ruta::findOrFail($idRuta);
                $precioTotal = $cantidadPersonas * $ruta->precio_actual;
                $saldo = $precioTotal - $montoPagado;

                DB::beginTransaction();

                // Crear RESERVA
                $reserva = Reserva::create([
                    'id_fecha' => $idFecha,
                    'fecha_reserva' => Carbon::now(),
                    'cantidad_personas' => $cantidadPersonas,
                    'precio_total' => $precioTotal,
                    'saldo' => $saldo,
                    'estado' => 'pendiente',
                ]);

                // Crear CLIENTE PRINCIPAL
                $cliente = Cliente::updateOrCreate(
                    ['numero_documento' => $datosReserva['numero_documento']],
                    [
                        'tipo_documento'   => $datosReserva['tipo_documento'],
                        'nombre'           => $datosReserva['nombre'],
                        'apellido'         => $datosReserva['apellido'],
                        'fecha_nacimiento' => $datosReserva['fecha_nacimiento'],
                        'email'            => $datosReserva['email'],
                        'telefono'         => $datosReserva['telefono'],
                        'pais'             => $datosReserva['pais'],
                        'region'           => $datosReserva['region'],
                        'ciudad'           => $datosReserva['ciudad'],
                    ]
                );

                // Asociar CLIENTE con RESERVA
                ReservaCliente::create([
                    'id_reserva' => $reserva->id_reserva,
                    'id_cliente' => $cliente->id_cliente,
                ]);

                // Acompañantes
                if (!empty($datosReserva['acompanantes'])) {
                    $acompanantes = json_decode($datosReserva['acompanantes'], true);
                    foreach ($acompanantes as $acompanante) {
                        $nuevo = Cliente::updateOrCreate(
                            ['numero_documento' => $acompanante['doc']],
                            [
                                'tipo_documento'   => $acompanante['tipo'],
                                'nombre'           => $acompanante['nombre'],
                                'apellido'         => $acompanante['apellido'],
                                'fecha_nacimiento' => $acompanante['fecha'],
                                'telefono'         => $acompanante['telefono'],
                            ]
                        );

                        ReservaCliente::create([
                            'id_reserva' => $reserva->id_reserva,
                            'id_cliente' => $nuevo->id_cliente,
                        ]);
                    }
                }

                // Registrar PAGO
                Pago::create([
                    'id_reserva' => $reserva->id_reserva,
                    'metodo_pago' => $metodoPago,
                    'monto_pagado' => $montoPagado,
                    'fecha_pago' => $fechaPago,
                ]);

                DB::commit();
                Log::info('Pago exitoso:', ['payment_id' => $paymentId, 'external_reference' => $externalReference]);
                
                Mail::to($cliente->email)->send(new ConfirmacionReserva(
                    $cliente,
                    $reserva,
                    $ruta,
                    $reserva->fechaDisponible
                ));

                // ✅ Limpiar sesión
                session()->forget('datos_reserva');

                return view('mercadopago.exito', [
                    'status' => $request->input('collection_status'),
                    'payment_id' => $paymentId,
                    'external_reference' => $externalReference,
                ]);
            }

            return view('mercadopago.fallo', [
                'error' => 'El pago no fue aprobado. Por favor, inténtalo nuevamente.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en el pago exitoso: ' . $e->getMessage());

            return view('mercadopago.fallo2', [
                'error' => 'Hubo un error al procesar la confirmación del pago.',
            ]);
        }
    }

    // Redirección cuando el pago falla
    public function failure(Request $request)
    {
        Log::warning('Redireccionado a FAILURE:', $request->all());
        return view('mercadopago.fallo');
    }
}
