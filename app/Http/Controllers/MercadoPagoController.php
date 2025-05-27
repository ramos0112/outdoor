<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Exceptions\MPApiException;

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
            "title" => $request->input('monbre_ruta', 'Reserva'),
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

        //back_urls con Ngrok
        $ngrok = env('NGROK_UR');

        $backUrls = [
            "success" => "$ngrok/mercadopago/success",
            "failure" => "$ngrok/mercadopago/failure",
        ];
        Log::info('Back URLs:', $backUrls);

       //6. URLs de redireccionamiento PARA PRODUCCION
        /* 
        $baseUrl = config('app.url');

        $backUrls = [
            "success" => "$baseUrl/mercadopago/success",
            "failure" => "$baseUrl/mercadopago/failure",
        ];
        */
        // 7. Crear preferencia de pago
        $preferenceData = [
            "items" => $items,
            "payer" => $payer,
            "back_urls" => $backUrls,
            "auto_return" => "approved", // Muy importante
            "external_reference" => uniqid("reserva_"),
        ];

        Log::info('Back URLs:', $backUrls);

        // 8. Enviar a Mercado Pago y Crear preferencia
        try {
            $client = new PreferenceClient();
            $preference = $client->create($preferenceData);

            Log::info('Preferencia creada:', (array) $preference);

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
        Log::info('Redireccionado a SUCCESS:', $request->all());
        return view('mercadopago.exito', [
            'status' => $request->input('collection_status'),
            'payment_id' => $request->input('payment_id'),
            'external_reference' => $request->input('external_reference')
        ]);
    }

    // Redirección cuando el pago falla
    public function failure(Request $request)
    {
        Log::warning('Redireccionado a FAILURE:', $request->all());
        return view('mercadopago.fallo');
    }
}
