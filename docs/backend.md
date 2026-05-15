# Documentación del Backend

## I. Resumen ejecutivo

El backend del proyecto AGENTS está estructurado como una aplicación **monolítica MVC tradicional** con **lógica centralizada en controladores**. No hay capa de servicios, repositorios, o use cases separados. Las validaciones se realizan con `$request->validate()` inline (no hay FormRequest classes). El flujo de negocio más crítico (reservas y pagos) está concentrado en dos controladores: `MercadoPagoController` y `ReservaClienteController`.

**Características arquitectónicas**:
- ✅ Autenticación con Jetstream + Sanctum
- ✅ Permisos con Spatie Permission (aunque mal aplicados en rutas)
- ✅ Logging básico con Spatie ActivityLog (solo en Ruta)
- ❌ Sin capa de servicios
- ❌ Sin FormRequest validation classes
- ❌ Sin Events/Listeners
- ❌ Sin Jobs para procesamiento asincrónico
- ❌ Sin Repositories/Data Access Layer

---

## II. Estructura de directorios del backend

```
app/
├── Http/
│   ├── Controllers/        (23 controladores)
│   │   ├── MercadoPagoController.php        ⚠️ SOBRECARGADO
│   │   ├── ReservaClienteController.php     ⚠️ LÓGICA COMENTADA
│   │   ├── ReservaController.php
│   │   ├── RutaController.php
│   │   ├── ClienteController.php
│   │   ├── GuiaController.php
│   │   ├── MovilidadController.php
│   │   ├── HomeController.php
│   │   ├── DashboardController.php
│   │   ├── [16 controllers más]
│   ├── Requests/           (NO EXISTE - sin FormRequest)
│   ├── Middleware/         (NO EXISTE - solo global)
│   └── [Otros]
├── Models/                 (18 modelos)
├── Mail/
│   └── ConfirmacionReserva.php         (Única notificación)
├── Policies/
│   └── TeamPolicy.php                  (Solo para Teams)
├── Events/                 (NO EXISTE)
├── Listeners/              (NO EXISTE)
├── Jobs/                   (NO EXISTE)
├── Services/               (NO EXISTE)
├── Repositories/           (NO EXISTE)
└── Traits/                 (NO EXISTE)
```

---

## III. Análisis de controladores por categoría

### 3.1 Controladores de lógica de negocio crítica

#### **MercadoPagoController** 🔴 CRÍTICO

**Responsabilidades**:
1. Recibir datos de reserva del formulario
2. Crear preferencia de MercadoPago
3. Procesar callback de éxito
4. Crear registros en BD (Reserva, Cliente, ReservaCliente, Pago)
5. Enviar email de confirmación

**Métodos**:
- `checkout(Request $request)` - 96 líneas
- `success(Request $request)` - 160 líneas
- `failure(Request $request)` - 3 líneas

**Problemas identificados**:

1. **Lógica complicada en una sola función**:
   ```php
   public function success(Request $request)
   {
       // 160 líneas de lógica
       // Validación de pago
       // Cálculo de precios
       // Crear Reserva
       // Crear Cliente
       // Asociar ReservaCliente
       // Procesar acompañantes (JSON)
       // Registrar Pago
       // Enviar mail
   }
   ```

2. **Sin validación de firma de MercadoPago**: ⚠️ VULNERABILIDAD
   - Confía ciegamente en `payment_id`
   - No valida IPN/webhook
   - Atacante puede llamar directamente sin pagar

3. **Almacenamiento en sesión**:
   ```php
   session(['datos_reserva' => $request->all()]);
   ```
   - Confianza en cliente para cantidad y precio
   - No recalcula en el server antes de crear pago

4. **Transacción sin rollback completo**:
   ```php
   DB::beginTransaction();
   // ... inserciones
   DB::commit();
   ```
   - Si email falla, ya está guardado
   - Sin compensación en error

5. **Manejo de acompañantes en JSON**:
   ```php
   $acompanantes = json_decode($datosReserva['acompanantes'], true);
   ```
   - Asume formato correcto
   - Sin validación de estructura
   - Potencial error si JSON está malformado

6. **Logging detallado pero sin abstracción**:
   ```php
   Log::info('Datos recibidos en checkout:', $request->all());
   ```
   - Registra datos sensibles (email, teléfono)

---

#### **ReservaClienteController**

**Responsabilidades**:
1. Mostrar formulario de reserva (público)
2. Procesar reserva (comentado/deshabilitado)

**Métodos**:
- `formulario($id_ruta)` - 5 líneas
- `store(Request $request)` - 70 líneas (TODO COMENTADO)

**Problemas**:

1. **Lógica completamente comentada**:
   ```php
   public function store(Request $request)
   {
       /*
       dd($request->all());
       DB::beginTransaction();
       // ... 70 líneas comentadas
       */
   }
   ```
   - No hay implementación funcional
   - Reserva directo sin pago está deshabilitado
   - Toda lógica fue movida a MercadoPagoController

2. **Inconsistencia**:
   - El formulario está en este controlador
   - El procesamiento está en MercadoPago
   - División confusa de responsabilidades

---

#### **ReservaController**

**Responsabilidades**:
1. Listar reservas administrativas
2. Buscar por DNI

**Métodos**:
- `index()` - 10 líneas
- `buscarPorDNI(Request $request)` - 22 líneas
- `[create, store, show, edit, update, destroy]` - vacíos

**Problemas**:

1. **Métodos CRUD sin implementar**:
   ```php
   public function create() { /* vacío */ }
   public function store(Request $request) { /* vacío */ }
   ```

2. **Permisos granulares mal aplicados**:
   ```php
   $this->middleware('can:reservas.gestionar')->only(['index', 'show']);
   $this->middleware('can:reservas.crear')->only(['create', 'store']);
   ```
   - Los permisos se definen pero los métodos están vacíos

3. **Búsqueda por DNI sin validación**:
   ```php
   public function buscarPorDNI(Request $request)
   {
       $dni = $request->input('dni');
       // Sin validar formato de DNI
       $reserva = Reserva::with(...)->whereHas('clientes', ...)->first();
   }
   ```

---

### 3.2 Controladores CRUD estándar (Patrón repetido)

#### **RutaController**

**Métodos completos**:
- `index()` - Lista rutas
- `store(Request $request)` - Validación + create
- `show($id)` - Retorna vista
- `update(Request $request, $id)` - Validación + update
- `destroy($id)` - Delete

**Validación inline**:
```php
$request->validate([
    'nombre_ruta' => 'required|string|max:255',
    'precio_regular' => 'nullable|numeric',
    'estado' => 'nullable|string',
]);
```

**Patrón repetido en**:
- ClienteController
- GuiaController
- PagoController
- MovilidadController
- (13 controladores más)

**Problemas comunes**:
1. Validación duplicada en `store()` y `update()`
2. Sin FormRequest para reutilizar reglas
3. Validación muy básica (sin reglas de negocio)
4. Sin inyección de dependencias

---

#### **ClienteController**

```php
public function store(Request $request)
{
    $request->validate([
        'numero_documento' => 'required|unique:clientes,numero_documento',
        'email' => 'required|email|unique:clientes,email',
    ]);

    Cliente::create([
        'nombre' => $request->nombre,
        'apellido' => $request->apellido,
        // ...
    ]);

    return redirect()->route('clientes.index')->with('success', '...');
}

public function update(Request $request, Cliente $cliente)
{
    $request->validate([
        'numero_documento' => 'required|unique:clientes,numero_documento,' . $cliente->id_cliente . ',id_cliente',
        'email' => 'required|email|unique:clientes,email,' . $cliente->id_cliente . ',id_cliente',
    ]);
    // ... update
}
```

**Duplicación clara**: Validación casi idéntica en `store()` y `update()`.

---

### 3.3 Controladores de reportes y analítica

#### **DashboardController**

**Responsabilidades**:
1. Calcular KPIs de rutas
2. Calcular KPIs de reservas
3. Calcular KPIs de clientes
4. Calcular KPIs de movilidad
5. Calcular KPIs de pagos

**Métodos**:
- `index()` - 180+ líneas

**Problemas**:

1. **Método gigante**:
   ```php
   public function index()
   {
       // Rutas activas/inactivas
       // Precio promedio
       // Top 5 rutas más vendidas (JOIN de 4 tablas)
       // Top 5 rutas por ingresos (JOIN de 4 tablas)
       // Reservas por estado
       // Reservas por mes
       // Clientes totales
       // Clientes con reservas
       // Clientes por mes
       // Clientes por región
       // Movilidad
       // Guías
       // Pagos
       // ... y todo pasado a la vista
   }
   ```

2. **Queries crudas sin abstracción**:
   ```php
   DB::table('reservas')
       ->join('fecha_disponibles', ...)
       ->join('rutas', ...)
       ->select(...)
       ->groupBy(...)
       ->get();
   ```
   - Difícil de mantener
   - Sin reutilización

3. **Sin caching**:
   - Cada vista del dashboard ejecuta todas estas queries
   - N+1 queries potenciales

---

#### **MovilidadReporteController**

**Responsabilidades**:
1. Rutas por fecha (PASO 1)
2. Movilidades por ruta (PASO 2)
3. Manifiesto por movilidad (PASO 3)

**Métodos**:
- `index()` - 1 línea
- `rutasPorFecha(Request $request)` - 15 líneas
- `movilidadesPorRuta(Request $request)` - 20 líneas
- `manifiestoPorMovilidad(Request $request)` - 35 líneas

**Problemas**:

1. **Queries complejas pero inline**:
   ```php
   public function manifiestoPorMovilidad(Request $request)
   {
       $data = DB::table('reservas as r')
           ->join('reserva_movilidads as rm', ...)
           ->join('fecha_disponibles as f', ...)
           ->join('rutas as ru', ...)
           ->join('reserva_clientes as rc', ...)
           ->join('clientes as c', ...)
           ->where('rm.id_movilidad', $request->id_movilidad)
           ->select(..., DB::raw("CONCAT(...)..."))
           ->get();
   }
   ```
   - 6 JOINs en una sola query
   - Sin validación de permisos de usuario

---

### 3.4 Controladores simples (HomeController)

```php
public function home()
{
    $rutas = Ruta::with('imagenes')->get();
    $rutasTrekking = Ruta::with('imagenes')->where('tipo', 'Trekking')->get();
    $rutasAventura = Ruta::with('imagenes')->where('tipo', 'Aventura')->get();
    return view('paguinas.home', compact(...));
}
```

**Problemas**:
1. Queries redundantes (carga todas las rutas 3 veces)
2. Sin paginación
3. Sin caché de imágenes

---

## IV. Análisis de validaciones

### 4.1 Patrón actual: Inline validation

Todos los controladores usan:
```php
$request->validate([
    'campo' => 'rule1|rule2',
]);
```

### 4.2 Validaciones detectadas

| Controlador | Campo | Reglas | Problema |
|------------|-------|--------|----------|
| RutaController | nombre_ruta | required\|string\|max:255 | OK |
| RutaController | precio_regular | nullable\|numeric | ¿Precio negativo? |
| ClienteController | numero_documento | required\|unique | Sin formato DNI |
| ClienteController | email | required\|email\|unique | OK |
| GuiaController | email | required\|email\|unique | OK |
| MovilidadReporteController | id_movilidad | required\|integer | OK |

### 4.3 Problemas de validación

1. **Sin FormRequest classes**: Validación no reutilizable
2. **Sin validación de negocio**:
   - Precio negativo permitido
   - DNI sin formato específico
   - Cantidad de personas sin mínimo
3. **Sin validación de disponibilidad**:
   - Fechas ya pasadas se pueden reservar
   - Capacidad de vehículo no se verifica

---

## V. Flujo de negocio: Reserva y Pago

```
┌─────────────────────────────────────────────────────────────────┐
│                    FRONTEND (Navegador)                        │
└─────────────────────────────────────────────────────────────────┘

1. GET /rutas/{id}/descripcion
   └─► HomeController@mostrarDescripcion
       └─► Ruta::with(['detalles', 'lugaresVisitar', 'serviciosIncluidos', 
                       'imagenes', 'fechasDisponibles'])
           └─► Vista con fechas disponibles

2. GET /reserva/{ruta}
   └─► ReservaClienteController@formulario
       └─► Ruta::with('fechasDisponibles')
           └─► Formulario HTML con CSRF token

3. Usuario completa formulario:
   - Nombre, apellido, DNI, email, teléfono
   - País, región, ciudad
   - Cantidad de personas
   - Acompañantes (JSON array)
   - Ruta, fecha

4. POST /checkout (PÚBLICO - sin auth)
   └─► MercadoPagoController@checkout
       
       ✅ Recibe:
       ├─ cantidad_personas (1)
       ├─ precio_actual (2)
       ├─ nombre_ruta (3)
       ├─ nombre (4)
       ├─ apellido (5)
       ├─ email (6)
       ├─ [otros campos]
       
       ✅ Procesa:
       ├─ $total = cantidad × precio_actual
       ├─ $montopagar = $total × 0.5  (50%)
       ├─ Crea PreferenceData
       ├─ Guarda todo en session['datos_reserva']
       │   └─► ⚠️ RIESGO: Sesión puede ser manipulada/robada
       ├─ PreferenceClient::create()
       └─ redirect($preference->init_point)

5. Redirect a MercadoPago Checkout
   └─► Usuario ingresa datos de pago

6. MercadoPago procesa y redirige
   └─► GET /mercadopago/success?payment_id=xxx
   
       ⚠️ VULNERABILIDAD: Sin validación de firma

7. MercadoPagoController@success
   
   ✅ Recibe:
   ├─ payment_id
   ├─ external_reference
   ├─ [otros parámetros]
   
   ❌ SIN VALIDAR firma de MercadoPago
   
   ✅ Procesa:
   ├─ PaymentClient::get($payment_id)  ← Llama API de MercadoPago
   │  └─► $payment->status === 'approved'
   │
   ├─ session('datos_reserva')  ← Recupera datos guardados
   │  └─► ⚠️ RIESGO: Confía en sesión del cliente
   │
   ├─ DB::beginTransaction()
   │
   ├─ Reserva::create([
   │     'id_fecha' => $datosReserva['id_fecha'],
   │     'cantidad_personas' => $datosReserva['cantidad_personas'],
   │     'precio_total' => cantidad × precio_actual,
   │     'saldo' => precio_total - monto_pagado,
   │     'estado' => 'pendiente'
   │  ])
   │  └─► ⚠️ RIESGO: Recalcula precio pero confía en cantidad
   │
   ├─ Cliente::updateOrCreate([
   │     'numero_documento' => $datosReserva['numero_documento']
   │  ], [...])
   │  └─► ✅ updateOrCreate previene duplicados
   │
   ├─ ReservaCliente::create(...)  ← Vincula cliente a reserva
   │
   ├─ Procesa acompañantes:
   │  ├─ foreach ($acompanantes) {
   │  │     $nuevo = Cliente::updateOrCreate(...)
   │  │     ReservaCliente::create(...)
   │  │  }
   │  └─► ⚠️ SIN VALIDACIÓN de estructura JSON
   │
   ├─ Pago::create([
   │     'id_reserva' => $reserva->id_reserva,
   │     'metodo_pago' => $metodoPago,
   │     'monto_pagado' => $montoPagado,
   │     'fecha_pago' => $fechaPago
   │  ])
   │
   ├─ DB::commit()
   │
   ├─ Mail::to($cliente->email)->send(
   │     new ConfirmacionReserva($cliente, $reserva, $ruta, ...)
   │  )
   │  └─► ✅ Envía email confirmación
   │
   ├─ session()->forget('datos_reserva')
   │
   └─ return view('mercadopago.exito', [...])

┌─────────────────────────────────────────────────────────────────┐
│              BASE DE DATOS (Inserciones)                        │
└─────────────────────────────────────────────────────────────────┘

INSERT INTO reservas (id_fecha, fecha_reserva, cantidad_personas, precio_total, saldo, estado)
VALUES (15, NOW(), 3, 1500.00, 750.00, 'pendiente');

INSERT INTO clientes (nombre, apellido, numero_documento, email, telefono, ...)
VALUES ('Juan', 'Pérez', '12345678', 'juan@example.com', '987654321', ...);

INSERT INTO reserva_clientes (id_reserva, id_cliente)
VALUES (42, 128);

INSERT INTO reserva_clientes (id_reserva, id_cliente)  -- acompañante 1
VALUES (42, 129);

INSERT INTO clientes (nombre, apellido, numero_documento, email, telefono, ...)
VALUES ('Maria', 'García', '87654321', 'maria@example.com', '9123456', ...);

INSERT INTO reserva_clientes (id_reserva, id_cliente)  -- acompañante 2
VALUES (42, 130);

INSERT INTO pagos (id_reserva, metodo_pago, monto_pagado, fecha_pago)
VALUES (42, 'card', 750.00, NOW());

┌─────────────────────────────────────────────────────────────────┐
│              EMAIL (Notificación)                               │
└─────────────────────────────────────────────────────────────────┘

Mail::to('juan@example.com')->send(new ConfirmacionReserva(...))

├─ MailableClass: ConfirmacionReserva
├─ Envelope: 'subject' => 'Confirmacion Reserva'
├─ Content: 'markdown' => 'emails.confirmacion'
├─ Data pasado:
│  ├─ $cliente (Cliente model)
│  ├─ $reserva (Reserva model)
│  ├─ $ruta (Ruta model)
│  └─ $fechaDisponible (FechaDisponible model)
└─ Template: resources/views/emails/confirmacion.blade.php
   └─ Renderiza Markdown con datos de reserva
```

---

## VI. Notificaciones y Correos

### 6.1 Mailable: ConfirmacionReserva

**Archivo**: `app/Mail/ConfirmacionReserva.php`

```php
class ConfirmacionReserva extends Mailable
{
    use Queueable, SerializesModels;
    
    public $cliente;
    public $reserva;
    public $ruta;
    public $fechaDisponible;
    
    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirmacion Reserva');
    }
    
    public function content(): Content
    {
        return new Content(markdown: 'emails.confirmacion');
    }
}
```

**Template**: `resources/views/emails/confirmacion.blade.php`

**Datos enviados**:
- Cliente: nombre, apellido, email, teléfono
- Reserva: id, fecha, cantidad_personas, precio_total, estado
- Ruta: nombre, tipo, dificultad
- FechaDisponible: fecha de viaje

**Llamada**:
```php
Mail::to($cliente->email)->send(new ConfirmacionReserva(
    $cliente,
    $reserva,
    $ruta,
    $reserva->fechaDisponible
));
```

**Problemas**:

1. **Sin control de errores**:
   - Si email falla, no hay reintentos
   - Sin fallback si SMTP está caído

2. **Sin Queue**:
   - Envío sincrónico en el request
   - Bloquea al usuario
   - Timeout si SMTP es lento

3. **Única notificación**:
   - Solo después de pago exitoso
   - Otros eventos sin notificación

4. **Sin template test**:
   - Template en `resources/views/emails/confirmacion.blade.php`
   - No verificable desde docs

---

## VII. Inyección de Dependencias

### 7.1 Patrón detectado: Mínima DI

**Sin usar**:
```php
public function __construct(SomeService $service)
{
    $this->service = $service;
}
```

**Usando**:
```php
// Facades
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

// Models directamente
$ruta = Ruta::findOrFail($id);

// En los métodos
public function index()
{
    $rutas = Ruta::all();  // Sin repositorio
}
```

### 7.2 Comunicación entre clases

```
HomeController
  ├─► Ruta::with('imagenes')
  └─► View::make(...)

MercadoPagoController
  ├─► PreferenceClient::create()
  ├─► PaymentClient::get()
  ├─► Ruta::findOrFail()
  ├─► Reserva::create()
  ├─► Cliente::updateOrCreate()
  ├─► ReservaCliente::create()
  ├─► Pago::create()
  ├─► Mail::to()
  └─► Log::info()

ReservaController
  ├─► Reserva::with([...])
  └─► response()->json()
```

**Problemas**:
- No hay abstracción
- Acoplamiento directo a modelos
- Difícil testear

---

## VIII. Auditoría de Backend: Deuda Técnica

### 8.1 Código duplicado

| Código | Ubicación 1 | Ubicación 2 | Líneas |
|--------|------------|-----------|--------|
| Validación de Cliente | ClienteController@store | ClienteController@update | 7 líneas |
| Validación de Ruta | RutaController@store | RutaController@update | 8 líneas |
| Validación de Guía | GuiaController@store | GuiaController@update | 3 líneas |
| Validación de Movilidad | MovilidadController@store | MovilidadController@update | 5 líneas |
| Crear Cliente | MercadoPagoController | ReservaClienteController | 10 líneas |
| Crear ReservaCliente | MercadoPagoController | ReservaClienteController | 3 líneas |

**Impacto**: 30+ líneas duplicadas de validación que podría consolidarse en FormRequest.

---

### 8.2 Controladores sobrecargados

| Controlador | Líneas | Responsabilidades | Métodos |
|------------|--------|------------------|---------|
| **MercadoPagoController** | 260+ | Preferencia, Validación, BD, Email | 3 |
| **DashboardController** | 180+ | 12 queries de reportes | 1 (index) |
| **MovilidadReporteController** | 95+ | 3 tipos de reportes | 4 |
| **RutaController** | 80+ | CRUD + Validaciones | 5 |
| **ClienteController** | 110+ | CRUD + Validaciones | 7 |

**Debería estar**: Máximo 60 líneas por controlador en una arquitectura bien dividida.

---

### 8.3 Deuda técnica identificada

#### 🔴 CRÍTICA

1. **Sin validación de MercadoPago**
   - Callbacks aceptan cualquier payment_id
   - Riesgo: Pago falso sin dinero real

2. **Confianza en sesión del cliente**
   - `session('datos_reserva')` sin re-validar
   - Riesgo: Manipulación de cantidad/precio

3. **Sin transacciones compensadas**
   - Si email falla, reserva ya está creada
   - Sin rollback completo

#### 🟡 IMPORTANTE

4. **Sin FormRequest classes**
   - Validación duplicada en 10+ lugares
   - Difícil mantener reglas consistentes

5. **Sin capa de servicios**
   - Lógica mixturada con HTTP
   - Difícil de testear
   - No reutilizable

6. **Sin Queue para emails**
   - Envío sincrónico bloquea request
   - Timeout si SMTP falla

7. **Queries complejas sin abstracción**
   - DashboardController: 6+ JOINs
   - MovilidadReporteController: queries dinámicas
   - Sin reutilización

8. **Sin caching**
   - Dashboard recalcula todo cada vista
   - Queries de reportes corren siempre

#### 🟠 MEDIO

9. **Métodos CRUD vacíos**
   - ReservaController: create, store, show, edit, update, destroy sin código
   - Confunde a desarrolladores

10. **Lógica comentada**
    - ReservaClienteController@store: 70 líneas comentadas
    - Código muerto dificulta comprensión

11. **Sin validación de negocio**
    - Fecha pasada se puede reservar
    - Capacidad de vehículo ignorada
    - Cantidad de personas sin mínimo

---

## IX. Patrones recomendados (vs. actual)

### 9.1 Validación

**Actual**:
```php
public function store(Request $request)
{
    $request->validate([...]);
    Cliente::create($request->all());
}

public function update(Request $request, Cliente $cliente)
{
    $request->validate([...]);  // DUPLICADO
    $cliente->update($request->all());
}
```

**Recomendado**:
```php
use App\Http\Requests\StoreClienteRequest;
use App\Http\Requests\UpdateClienteRequest;

public function store(StoreClienteRequest $request)
{
    Cliente::create($request->validated());
}

public function update(UpdateClienteRequest $request, Cliente $cliente)
{
    $cliente->update($request->validated());
}
```

### 9.2 Lógica de negocio

**Actual**:
```php
public function success(Request $request)
{
    $reserva = Reserva::create([...]);
    $cliente = Cliente::updateOrCreate(...);
    ReservaCliente::create(...);
    // ... más lógica
    Mail::send(...);
}
```

**Recomendado**:
```php
use App\Services\ReservaService;

public function success(Request $request)
{
    try {
        $reserva = ReservaService::crearDesdeMP($request);
        return view('success', compact('reserva'));
    } catch (Exception $e) {
        // error
    }
}
```

### 9.3 Queries complejas

**Actual**:
```php
$rutasVendidas = DB::table('reservas')
    ->join('fecha_disponibles', ...)
    ->join('rutas', ...)
    ->select(...)
    ->get();
```

**Recomendado**:
```php
use App\Repositories\RutaRepository;

public function index()
{
    $rutas = RutaRepository::obtenerMasVendidas();
}
```

---

## X. Estructura de carpetas recomendada

```
app/
├── Http/
│   ├── Controllers/      (Thin controllers)
│   ├── Requests/         (FormRequest classes) ← FALTA
│   └── Resources/        (API resources) ← FALTA
├── Services/             (Business logic) ← FALTA
│   ├── ReservaService.php
│   ├── MercadoPagoService.php
│   └── ClienteService.php
├── Repositories/         (Data access) ← FALTA
│   ├── RutaRepository.php
│   └── ReservaRepository.php
├── Events/               (Domain events) ← FALTA
│   ├── ReservaCreada.php
│   └── PagoRealizado.php
├── Listeners/            (Event handlers) ← FALTA
│   ├── EnviarConfirmacion.php
│   └── ActualizarInventario.php
├── Jobs/                 (Async tasks) ← FALTA
│   ├── EnviarEmailJob.php
│   └── GenerarReportesJob.php
├── Models/
├── Mail/
├── Policies/
└── Traits/
```

---

## XI. Resumen ejecutivo de auditoría

| Aspecto | Estado | Calificación |
|--------|--------|-------------|
| **Arquitectura** | Monolítica, sin servicios | ⚠️ |
| **Validaciones** | Inline, duplicadas | ⚠️ |
| **Lógica de negocio** | Centralizada en controllers | ❌ |
| **Notificaciones** | 1 Mailable, sin Queue | ⚠️ |
| **Seguridad (MercadoPago)** | Sin validación de firma | 🔴 CRÍTICA |
| **Testabilidad** | Baja, sin abstracción | ❌ |
| **Mantenibilidad** | Código duplicado, métodos largos | ⚠️ |
| **Performance** | Sin caching, queries sin optimizar | ⚠️ |

**Score general**: 4.5/10 - Proyecto funcional pero con deuda técnica significativa.

