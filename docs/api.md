# Documentación de API

## I. Visión general

El proyecto tiene una API mínima definida en `routes/api.php` y algunos endpoints internos registrados en `routes/web.php` para operaciones AJAX/dinámicas.

La mayoría del sistema es una aplicación web tradicional con Blade + Livewire. No hay una API completa REST/JSON pública.

## II. Endpoints de API en routes/api.php

### 2.1 Obtener usuario autenticado

**Endpoint**:
```
GET /api/user
```

**Middleware**:
- `auth:sanctum`

**Descripción**:
Retorna los datos del usuario actualmente autenticado usando token Sanctum.

**Parámetros**:
- Ninguno (requiere Authorization token en header)

**Respuesta exitosa (200 OK)**:
```json
{
  "id": 1,
  "name": "Admin",
  "email": "admin@gmail.com",
  "email_verified_at": "2025-04-01T10:30:00.000000Z",
  "current_team_id": null,
  "profile_photo_path": null,
  "created_at": "2025-04-01T10:30:00.000000Z",
  "updated_at": "2025-04-01T10:30:00.000000Z"
}
```

**Respuesta no autenticada (401 Unauthorized)**:
```json
{
  "message": "Unauthenticated."
}
```

**Ejemplo de uso con cURL**:
```bash
curl -H "Authorization: Bearer {token}" \
     -H "Accept: application/json" \
     http://localhost:8000/api/user
```

## III. Endpoints internos (AJAX/SPA)

### 3.1 Búsqueda de cliente por documento

**Endpoint**:
```
GET /buscar-cliente/{numero_documento}
```

**Middleware**:
- `auth:sanctum`
- `config('jetstream.auth_session')`
- `verified`

**Descripción**:
Busca un cliente por número de documento (DNI). Usualmente llamado desde AJAX en formularios de reserva.

**Parámetros**:
- `numero_documento` (string, URI parameter) - Número de DNI/documento a buscar

**Respuesta exitosa (200 OK)**:
Si el cliente existe:
```json
{
  "id_cliente": 5,
  "nombre": "Juan",
  "apellido": "Pérez",
  "tipo_documento": "DNI",
  "numero_documento": "12345678",
  "fecha_nacimiento": "1990-05-15",
  "email": "juan@example.com",
  "telefono": "987654321",
  "pais": "Perú",
  "region": "Lima",
  "ciudad": "Lima",
  "created_at": "2025-04-02T14:20:00.000000Z",
  "updated_at": "2025-04-02T14:20:00.000000Z"
}
```

Si no existe:
```json
{
  "error": "Cliente no encontrado"
}
```
o respuesta vacía `null`.

**Controlador**: `ListarReservasController@buscarPorDocumento`

---

### 3.2 Obtener fechas disponibles por ruta

**Endpoint**:
```
GET /api/fechas-por-ruta/{id}
```

**Middleware**:
- `auth:sanctum`
- `config('jetstream.auth_session')`
- `verified`

**Descripción**:
Retorna todas las fechas disponibles para una ruta específica. Usado en dropdowns dinámicos.

**Parámetros**:
- `id` (integer, URI parameter) - ID de la ruta (`id_ruta`)

**Respuesta exitosa (200 OK)**:
```json
[
  {
    "id_fecha": 10,
    "id_ruta": 3,
    "fecha": "2026-05-15",
    "created_at": "2025-04-01T10:00:00.000000Z",
    "updated_at": "2025-04-01T10:00:00.000000Z"
  },
  {
    "id_fecha": 11,
    "id_ruta": 3,
    "fecha": "2026-05-22",
    "created_at": "2025-04-01T10:00:00.000000Z",
    "updated_at": "2025-04-01T10:00:00.000000Z"
  },
  {
    "id_fecha": 12,
    "id_ruta": 3,
    "fecha": "2026-05-29",
    "created_at": "2025-04-01T10:00:00.000000Z",
    "updated_at": "2025-04-01T10:00:00.000000Z"
  }
]
```

**Respuesta si no hay fechas (200 OK)**:
```json
[]
```

**Controlador**: `ListarReservasController@obtenerFechasPorRuta`

---

## IV. Integración MercadoPago

### 4.1 Descripción general

El flujo de pago está integrado en el frontend con formularios tradicionales, no es una API REST pura. Sin embargo, hay tres puntos de integración:

1. **POST /checkout** - Inicia sesión de pago
2. **GET /mercadopago/success** - Callback de éxito
3. **GET /mercadopago/failure** - Callback de fallo

### 4.2 Endpoint de checkout

**Endpoint**:
```
POST /checkout
```

**Middleware**:
- Ninguno (público)

**Descripción**:
Genera una preferencia de pago en MercadoPago y redirige al checkout de MercadoPago.

**Parámetros de entrada** (generalmente POST form data):
Según el controlador `MercadoPagoController@checkout`:
```php
$datos = [
    'cantidad_personas' => request('cantidad_personas'),
    'precio_total' => request('precio_total'),
    'nombre' => request('nombre'),
    'apellido' => request('apellido'),
    'email' => request('email'),
    'telefono' => request('telefono'),
    'dni' => request('dni'),
    // ... otros campos
];
```

**Flujo esperado**:

1. Cliente envía POST /checkout con datos de reserva
2. Controlador valida datos
3. Crea preferencia de MercadoPago con `PreferenceClient::create()`
4. Retorna `init_point` (URL de checkout)
5. Redirige a MercadoPago

**Respuesta / Redirect**:
```
HTTP/1.1 302 Found
Location: https://www.mercadopago.com.ar/checkout/v1/redirect?pref_id=xxxxxxxxxxxxx
```

**Parámetros de preferencia (MercadoPago)**:
```json
{
  "items": [
    {
      "title": "Tour - Nombre Ruta",
      "quantity": 1,
      "unit_price": 500.00,
      "currency_id": "PEN"
    }
  ],
  "payer": {
    "name": "Juan",
    "surname": "Pérez",
    "email": "juan@example.com",
    "phone": {
      "number": "987654321"
    }
  },
  "back_urls": {
    "success": "http://localhost:8000/mercadopago/success",
    "failure": "http://localhost:8000/mercadopago/failure",
    "pending": "http://localhost:8000/mercadopago/pending"
  },
  "auto_return": "approved",
  "external_reference": "uuid-unico-reserva"
}
```

**Controlador**: `MercadoPagoController@checkout`

---

### 4.3 Callback de éxito

**Endpoint**:
```
GET /mercadopago/success
```

**Query Parameters**:
- `payment_id` - ID de la transacción en MercadoPago
- `status` - Estado del pago (ej: "approved")
- `merchant_order_id` - ID de orden (opcional)
- `preference_id` - ID de preferencia

**Middleware**:
- Ninguno (callback externo)

**Descripción**:
MercadoPago redirige aquí después de pago exitoso. El controlador:
1. Valida que el pago sea real consultando MercadoPago
2. Extrae monto, método, fecha
3. Recupera datos de sesión (`session('datos_reserva')`)
4. Crea Reserva, Clientes, ReservaClientes, Pagos en BD (transacción)
5. Envía email de confirmación
6. Retorna vista de éxito

**Lógica del controlador**:
```php
public function success(Request $request)
{
    try {
        $payment_id = $request->query('payment_id');
        
        // Consultar MercadoPago para validar pago
        $payment = PaymentClient::get($payment_id);
        
        if ($payment['response']['status'] != 'approved') {
            return redirect('/mercadopago/failure');
        }
        
        $datos_reserva = session('datos_reserva');
        
        DB::beginTransaction();
        
        // Crear Reserva
        $reserva = Reserva::create([
            'id_fecha' => $datos_reserva['id_fecha'],
            'fecha_reserva' => now(),
            'cantidad_personas' => $datos_reserva['cantidad_personas'],
            'precio_total' => $datos_reserva['precio_total'],
            'saldo' => $datos_reserva['precio_total'] / 2,  // 50% pendiente
            'estado' => 'Pendiente'  // Hasta confirmar 50% restante
        ]);
        
        // Crear Cliente principal
        $cliente = Cliente::firstOrCreate([
            'numero_documento' => $datos_reserva['dni']
        ], [
            'nombre' => $datos_reserva['nombre'],
            'apellido' => $datos_reserva['apellido'],
            'email' => $datos_reserva['email'],
            'telefono' => $datos_reserva['telefono']
        ]);
        
        // Asociar cliente a reserva
        $reserva->clientes()->attach($cliente->id_cliente);
        
        // Crear registros de acompañantes si existen
        foreach ($datos_reserva['acompanantes'] as $acompanante) {
            $cli_acompanante = Cliente::create($acompanante);
            $reserva->clientes()->attach($cli_acompanante->id_cliente);
        }
        
        // Registrar pago
        Pago::create([
            'id_reserva' => $reserva->id_reserva,
            'metodo_pago' => 'MercadoPago',
            'monto_pagado' => $payment['response']['transaction_amount'],
            'fecha_pago' => now()
        ]);
        
        DB::commit();
        
        // Enviar email
        Mail::send(new ConfirmacionReserva($reserva, $cliente));
        
        return view('mercadopago.success', ['reserva' => $reserva]);
        
    } catch (Exception $e) {
        DB::rollback();
        Log::error('Error en pago', ['error' => $e->message]);
        return redirect('/mercadopago/failure');
    }
}
```

**Respuesta esperada**:
Vista HTML con confirmación de reserva, detalles y email de confirmación enviado.

**Controlador**: `MercadoPagoController@success`

**VULNERABILIDAD CRÍTICA**:
- No valida firma de MercadoPago
- Confía ciegamente en el query parameter `payment_id`
- Un atacante podría llamar directamente a `/mercadopago/success?payment_id=xxx` sin pagar

---

### 4.4 Callback de fallo

**Endpoint**:
```
GET /mercadopago/failure
```

**Query Parameters**:
- `payment_id` (opcional)
- `status` - Estado de fallo
- `preference_id` (opcional)

**Middleware**:
- Ninguno (callback externo)

**Descripción**:
MercadoPago redirige aquí si el pago es rechazado, cancelado o llega al timeout.

**Lógica del controlador**:
```php
public function failure(Request $request)
{
    $razon = $request->query('reason', 'desconocida');
    return view('mercadopago.failure', ['razon' => $razon]);
}
```

**Respuesta esperada**:
Vista HTML mostrando mensaje de error y opción para reintentar.

**Controlador**: `MercadoPagoController@failure`

---

## V. Flujo de datos en el proceso de reserva

```
┌────────────────────────────────────────────────────────────────────┐
│                     CLIENTE (FRONTEND)                             │
└────────────────────────────────────────────────────────────────────┘

GET /rutas/tipo/Trekking
│
├─► Obtiene lista de tours
│
GET /rutas/{id}/descripcion
│
├─► Obtiene detalles, fechas, lugares, servicios
│
GET /reserva/{ruta}
│
├─► Formulario de reserva HTML
│
┌─────────────────────────────────────────┐
│ Usuario completa:                       │
│ - Nombre, apellido, email, teléfono    │
│ - DNI                                   │
│ - Cantidad de personas                  │
│ - Acompañantes (nombre, DNI)            │
│ - Selecciona fecha disponible           │
└─────────────────────────────────────────┘
│
POST /reserva
│
├─► ReservaClienteController@store
├─► Valida datos del formulario
├─► Almacena en session('datos_reserva')
├─► Retorna confirmación parcial o redirige
│
POST /checkout
│
├─► MercadoPagoController@checkout
├─► Construye preferencia MercadoPago
│   └─► Items (tour + cantidad)
│   └─► Payer info
│   └─► Back URLs
├─► PreferenceClient::create($preferencia)
├─► Obtiene init_point
│
HTTP 302 Redirect
│
https://www.mercadopago.com.ar/checkout/v1/redirect?pref_id=...
│
┌──────────────────────────────────────────────────────────────┐
│            MERCADOPAGO (PLATAFORMA EXTERNA)                 │
└──────────────────────────────────────────────────────────────┘
│
├─► Usuario ingresa datos de tarjeta
├─► Valida pago
├─► Procesa transacción
│
├──────────┬──────────┤
│          │          │
APROBADO   RECHAZADO  CANCELADO
│          │          │
HTTP 302   HTTP 302   HTTP 302
Redirect   Redirect   Redirect
│          │          │
GET /mercadopago/success?payment_id=...
│
├─► MercadoPagoController@success
├─► PaymentClient::get($payment_id) ← VALIDACIÓN
├─► session('datos_reserva')
├─► BEGIN TRANSACTION
│
├─► CREATE Reserva (estado: Pendiente, 50% pagado)
├─► CREATE/UPDATE Cliente (datos principales)
├─► CREATE ReservaCliente links
├─► CREATE Acompañantes (otros clientes)
├─► CREATE Pago record
│
├─► COMMIT
├─► Mail::send ConfirmacionReserva
├─► Retorna vista success.blade.php
│
GET /mercadopago/failure?reason=...
│
├─► MercadoPagoController@failure
├─► Retorna vista failure.blade.php
└─► Usuario puede reintentar

┌────────────────────────────────────────────────────────────────────┐
│                  BASE DE DATOS (BACKEND)                          │
└────────────────────────────────────────────────────────────────────┘

Inserciones realizadas:

TABLE: reservas
│ id_reserva │ id_fecha │ fecha_reserva   │ cantidad_personas │ precio_total │ saldo  │ estado    │
├────────────┼──────────┼─────────────────┼───────────────────┼──────────────┼────────┼───────────┤
│ 42         │ 15       │ 2026-05-08      │ 3                 │ 1500.00      │ 750.00 │ Pendiente │

TABLE: clientes
│ id_cliente │ nombre │ apellido │ numero_documento │ email       │ telefono │
├────────────┼────────┼──────────┼──────────────────┼─────────────┼──────────┤
│ 128        │ Juan   │ Pérez    │ 12345678         │ juan@em.com │ 9876543  │
│ 129        │ Maria  │ García   │ 87654321         │ m@em.com    │ 9123456  │
│ 130        │ Carlos │ López    │ 11223344         │ c@em.com    │ 9555555  │

TABLE: reserva_clientes (pivot)
│ id_reserva │ id_cliente │ created_at │
├────────────┼────────────┼────────────┤
│ 42         │ 128        │ 2026-05-08 │
│ 42         │ 129        │ 2026-05-08 │
│ 42         │ 130        │ 2026-05-08 │

TABLE: pagos
│ id_pago │ id_reserva │ metodo_pago  │ monto_pagado │ fecha_pago  │
├─────────┼────────────┼──────────────┼──────────────┼─────────────┤
│ 85      │ 42         │ MercadoPago  │ 750.00       │ 2026-05-08  │

EMAIL ENVIADO:
To: juan@em.com
Subject: Confirmación de tu reserva - Tour XYZ
Body: Detalles de reserva, # confirmación, instrucciones de pago 50% restante
```

---

## VI. Respuestas de error esperadas

### 6.1 MercadoPago - Pago no validado

Si `PaymentClient::get($payment_id)` falla o status ≠ "approved":

```php
return redirect('/mercadopago/failure?reason=validacion_fallida');
```

### 6.2 Validación de formulario fallida

Según Laravel FormRequest, si hay errores:

```php
return back()->withErrors($validator)->withInput();
```

Retorna a la vista anterior con mensajes de error.

### 6.3 Cliente no encontrado en búsqueda

```json
{
  "message": "No records found"
}
```

O respuesta vacía `null` (según implementación).

---

## VII. Auditoría de seguridad de API

| Punto | Estado | Descripción |
|-------|--------|------------|
| **Authentication** | ✅ | `/api/user` usa `auth:sanctum` |
| **MercadoPago success** | ❌ **CRÍTICA** | No valida firma ni estado del pago antes de crear registros |
| **MercadoPago failure** | ⚠️ | Solo retorna vista, sin validación |
| **CORS** | ? | No visible en rutas; revisar middleware en app.php |
| **Rate limiting** | ❌ | No implementado en endpoints públicos |
| **Validación de entrada** | ? | Depende de FormRequest en controladores |
| **SQL Injection** | ✅ | Usando Eloquent, no queries SQL directas |
| **CSRF** | ✅ | Incluido globalmente, pero callbacks MP no lo requieren |

---

## VIII. Recomendaciones de seguridad para API

1. **Validar firma de MercadoPago**:
   - Implementar verificación de firma en `/mercadopago/success`
   - Usar `MercadoPago\SDK\Security` para validar IPN

2. **Implementar webhooks**:
   - Usar IPN (Instant Payment Notification) de MercadoPago en lugar de redirecciones
   - Esto permite confirmar pagos de forma asincrónica y segura

3. **Rate limiting**:
   - Agregar `throttle` middleware a `/checkout` y `/reserva`
   - Ej: `Route::post('/checkout', [...]))->middleware('throttle:5,1');`

4. **Validación adicional**:
   - Verificar que `precio_total` coincida con el cálculo en BD
   - No confiar en valores enviados por cliente

5. **Logging**:
   - Registrar todos los pagos en tabla de auditoría
   - Incluir timestamp, usuario, monto, estado

