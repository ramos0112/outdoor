# Documentación de Rutas Web

## I. Resumen general

El proyecto utiliza un sistema de rutas dividido en dos categorías principales:
1. **Rutas públicas**: accesibles sin autenticación (inicio, blogs, descripción de tours, formulario de reserva)
2. **Rutas protegidas**: requieren autenticación Jetstream (gestión de recursos administrativos)

Todas las rutas están definidas en `routes/web.php`. El archivo `routes/api.php` contiene solo un endpoint de API simple.

## II. Rutas públicas (sin protección de autenticación)

| Método | URL | Nombre | Controlador@Método | Descripción |
|--------|-----|--------|-------------------|------------|
| GET | `/` | `home` | `HomeController@home` | Página principal con tours destacados |
| GET | `/blog` | `blog.web` | `HomeController@blog` | Página de blog/noticias |
| GET | `/rutas/tipo/{tipo}` | `rutas.tipo` | `HomeController@rutasPorTipo` | Filtra tours por tipo (ej: Trekking, Aventura) |
| GET | `/rutas/{id_ruta}/descripcion` | `rutas.descripcion` | `HomeController@mostrarDescripcion` | Muestra detalles completos de una ruta individual |
| GET | `/reserva/{ruta}` | `reserva.formulario` | `ReservaClienteController@formulario` | Formulario de reserva para invitados |
| POST | `/reserva` | `reservas.store` | `ReservaClienteController@store` | Procesa y almacena la reserva del cliente |

### 2.1 Observaciones de rutas públicas

- La ruta `GET /rutas/{id_ruta}/descripcion` implementa validación de parámetro: `.where('id_ruta', '[0-9]+')` para garantizar que sea numérico.
- No hay restricción CSRF explícita en estas rutas públicas, lo que es correcto para GET pero POST requiere validación CSRF global.
- No hay rate limiting visible, lo que podría ser un riesgo en `POST /reserva` (invitados podrían hacer spam de reservas).

## III. Rutas de MercadoPago (integración de pagos)

| Método | URL | Nombre | Controlador@Método | Descripción | Protección |
|--------|-----|--------|-------------------|------------|-----------|
| POST | `/checkout` | `mercadopago.checkout` | `MercadoPagoController@checkout` | Inicia sesión de pago con MercadoPago | Ninguna (pública) |
| GET | `/mercadopago/success` | `mercadopago.success` | `MercadoPagoController@success` | Callback después de pago exitoso | Ninguna (callback externo) |
| GET | `/mercadopago/failure` | `mercadopago.failure` | `MercadoPagoController@failure` | Callback después de pago fallido | Ninguna (callback externo) |

### 3.1 Flujo de pago

```
1. Usuario llena formulario en /reserva/{ruta}
2. POST /reserva → Valida datos, almacena en sesión
3. Click en "Pagar 50%"
4. POST /checkout → Genera preferencia de MercadoPago
5. Redirect a MercadoPago checkout
6. Usuario paga en MercadoPago
7. MercadoPago redirige a /mercadopago/success o /mercadopago/failure
8. Success: Crea registros en BD (Reserva, Cliente, Pagos)
9. Success: Envía email de confirmación
```

### 3.2 Observaciones críticas de seguridad

- **RIESGO**: Las rutas de MercadoPago no validan el origen de callbacks. Un atacante podría llamar directamente a `/mercadopago/success` sin realizar pago real.
- **RECOMENDACIÓN**: Implementar validación de firma de MercadoPago o webhook tokens antes de crear registros en BD.

## IV. Rutas protegidas (requieren autenticación Jetstream)

Todas las rutas protegidas están dentro del middleware:
```php
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(...)
```

Esto requiere:
- `auth:sanctum`: Usuario autenticado
- `jetstream.auth_session`: Sesión válida de Jetstream
- `verified`: Email verificado

### 4.1 Dashboard y perfil

| Método | URL | Nombre | Controlador@Método | Descripción |
|--------|-----|--------|-------------------|------------|
| GET | `/dashboard` | `dashboard` | `DashboardController@index` | Panel principal con KPIs y gráficos |
| GET | `/Profile` | — | `UsuarioController@Perfil` | Perfil del usuario autenticado |

### 4.2 Gestión de reservas

| Método | URL | Nombre | Controlador@Método | Descripción |
|--------|-----|--------|-------------------|------------|
| GET | `/gestionreservas` | `gestionreservas.index` | `ReservaController@index` | Lista todas las reservas |
| GET | `/gestionreservas/{id}` | `gestionreservas.show` | `ReservaController@show` | Ver detalles de una reserva |
| GET | `/gestionreservas/create` | `gestionreservas.create` | `ReservaController@create` | Formulario para crear reserva |
| POST | `/gestionreservas` | `gestionreservas.store` | `ReservaController@store` | Almacenar nueva reserva |
| GET | `/gestionreservas/{id}/edit` | `gestionreservas.edit` | `ReservaController@edit` | Formulario para editar reserva |
| PUT | `/gestionreservas/{id}` | `gestionreservas.update` | `ReservaController@update` | Actualizar reserva existente |
| DELETE | `/gestionreservas/{id}` | `gestionreservas.destroy` | `ReservaController@destroy` | Eliminar reserva |
| POST | `/gestionreservas/buscar` | `gestionreservas.buscar` | `ReservaController@buscarPorDNI` | Buscar reservas por DNI |
| GET | `/listareservas` | `listareservas.index` | `ListarReservasController@index` | Lista alternativa de reservas |
| GET | `/listareservas/{id}` | `listareservas.show` | `ListarReservasController@show` | Ver reserva desde lista |
| GET | `/buscar-cliente/{numero_documento}` | — | `ListarReservasController@buscarPorDocumento` | API para búsqueda AJAX |
| GET | `/api/fechas-por-ruta/{id}` | — | `ListarReservasController@obtenerFechasPorRuta` | API para obtener fechas disponibles |

### 4.3 Gestión de tours y datos relacionados

| Método | URL | Nombre | Controlador@Método | Descripción |
|--------|-----|--------|-------------------|------------|
| GET | `/rutas` | `rutas.index` | `RutaController@index` | Lista de tours |
| GET | `/rutas/create` | `rutas.create` | `RutaController@create` | Crear nuevo tour |
| POST | `/rutas` | `rutas.store` | `RutaController@store` | Almacenar tour |
| GET | `/rutas/{id}` | `rutas.show` | `RutaController@show` | Ver detalles de tour |
| GET | `/rutas/{id}/edit` | `rutas.edit` | `RutaController@edit` | Editar tour |
| PUT | `/rutas/{id}` | `rutas.update` | `RutaController@update` | Actualizar tour |
| DELETE | `/rutas/{id}` | `rutas.destroy` | `RutaController@destroy` | Eliminar tour |
| GET | `/detalleruta` | `detalleruta.index` | `DetalleRutaController@index` | Lista de detalles de ruta |
| POST | `/detalleruta` | `detalleruta.store` | `DetalleRutaController@store` | Crear detalle |
| GET | `/detalleruta/{id}/edit` | `detalleruta.edit` | `DetalleRutaController@edit` | Editar detalle |
| PUT | `/detalleruta/{id}` | `detalleruta.update` | `DetalleRutaController@update` | Actualizar detalle |
| DELETE | `/detalleruta/{id}` | `detalleruta.destroy` | `DetalleRutaController@destroy` | Eliminar detalle |
| GET | `/fechas` | `fechas.index` | `FechaDisponibleController@index` | Lista de fechas disponibles |
| POST | `/fechas` | `fechas.store` | `FechaDisponibleController@store` | Crear fecha disponible |
| GET | `/fechas/{id}/edit` | `fechas.edit` | `FechaDisponibleController@edit` | Editar fecha |
| PUT | `/fechas/{id}` | `fechas.update` | `FechaDisponibleController@update` | Actualizar fecha |
| DELETE | `/fechas/{id}` | `fechas.destroy` | `FechaDisponibleController@destroy` | Eliminar fecha |
| GET | `/lugares` | `lugares.index` | `LugarVisitarController@index` | Lista de lugares a visitar |
| POST | `/lugares` | `lugares.store` | `LugarVisitarController@store` | Crear lugar |
| PUT | `/lugares/{id}` | `lugares.update` | `LugarVisitarController@update` | Actualizar lugar |
| DELETE | `/lugares/{id}` | `lugares.destroy` | `LugarVisitarController@destroy` | Eliminar lugar |
| GET | `/servicios` | `servicios.index` | `ServicioIncluidoController@index` | Lista de servicios incluidos |
| POST | `/servicios` | `servicios.store` | `ServicioIncluidoController@store` | Crear servicio |
| PUT | `/servicios/{id}` | `servicios.update` | `ServicioIncluidoController@update` | Actualizar servicio |
| DELETE | `/servicios/{id}` | `servicios.destroy` | `ServicioIncluidoController@destroy` | Eliminar servicio |
| GET | `/imagen` | `imagen.index` | `ImagenController@index` | Lista de imágenes |
| POST | `/imagen` | `imagen.store` | `ImagenController@store` | Subir imagen |
| DELETE | `/imagen/{id}` | `imagen.destroy` | `ImagenController@destroy` | Eliminar imagen |

### 4.4 Gestión de movilidad (vehículos)

| Método | URL | Nombre | Controlador@Método | Descripción |
|--------|-----|--------|-------------------|------------|
| GET | `/movilidades` | `movilidades.index` | `MovilidadController@index` | Lista de vehículos |
| GET | `/movilidades/create` | `movilidades.create` | `MovilidadController@create` | Crear vehículo |
| POST | `/movilidades` | `movilidades.store` | `MovilidadController@store` | Almacenar vehículo |
| GET | `/movilidades/{id}` | `movilidades.show` | `MovilidadController@show` | Ver vehículo |
| GET | `/movilidades/{id}/edit` | `movilidades.edit` | `MovilidadController@edit` | Editar vehículo |
| PUT | `/movilidades/{id}` | `movilidades.update` | `MovilidadController@update` | Actualizar vehículo |
| DELETE | `/movilidades/{id}` | `movilidades.destroy` | `MovilidadController@destroy` | Eliminar vehículo |
| GET | `/reservasmovilidad` | `reservasmovilidad.index` | `ReservaMovilidadController@index` | Lista de asignaciones reserva-vehículo |
| POST | `/reservasmovilidad` | `reservasmovilidad.store` | `ReservaMovilidadController@store` | Asignar vehículo a reserva |
| DELETE | `/reservasmovilidad/{id}` | `reservasmovilidad.destroy` | `ReservaMovilidadController@destroy` | Desasignar vehículo |

### 4.5 Gestión de clientes, guías y pagos

| Método | URL | Nombre | Controlador@Método | Descripción |
|--------|-----|--------|-------------------|------------|
| GET | `/clientes` | `clientes.index` | `ClienteController@index` | Lista de clientes |
| GET | `/clientes/create` | `clientes.create` | `ClienteController@create` | Crear cliente |
| POST | `/clientes` | `clientes.store` | `ClienteController@store` | Almacenar cliente |
| GET | `/clientes/{id}` | `clientes.show` | `ClienteController@show` | Ver cliente |
| GET | `/clientes/{id}/edit` | `clientes.edit` | `ClienteController@edit` | Editar cliente |
| PUT | `/clientes/{id}` | `clientes.update` | `ClienteController@update` | Actualizar cliente |
| DELETE | `/clientes/{id}` | `clientes.destroy` | `ClienteController@destroy` | Eliminar cliente |
| GET | `/guias` | `guias.index` | `GuiaController@index` | Lista de guías |
| GET | `/guias/create` | `guias.create` | `GuiaController@create` | Crear guía |
| POST | `/guias` | `guias.store` | `GuiaController@store` | Almacenar guía |
| GET | `/guias/{id}` | `guias.show` | `GuiaController@show` | Ver guía |
| GET | `/guias/{id}/edit` | `guias.edit` | `GuiaController@edit` | Editar guía |
| PUT | `/guias/{id}` | `guias.update` | `GuiaController@update` | Actualizar guía |
| DELETE | `/guias/{id}` | `guias.destroy` | `GuiaController@destroy` | Eliminar guía |
| GET | `/pagos` | `pagos.index` | `PagoController@index` | Lista de pagos |
| GET | `/pagos/create` | `pagos.create` | `PagoController@create` | Crear pago |
| POST | `/pagos` | `pagos.store` | `PagoController@store` | Almacenar pago |
| GET | `/pagos/{id}` | `pagos.show` | `PagoController@show` | Ver pago |
| GET | `/pagos/{id}/edit` | `pagos.edit` | `PagoController@edit` | Editar pago |
| PUT | `/pagos/{id}` | `pagos.update` | `PagoController@update` | Actualizar pago |
| DELETE | `/pagos/{id}` | `pagos.destroy` | `PagoController@destroy` | Eliminar pago |

### 4.6 Gestión de roles y permisos

| Método | URL | Nombre | Controlador@Método | Descripción |
|--------|-----|--------|-------------------|------------|
| GET | `/roles` | `roles.index` | `RoleController@index` | Lista de roles |
| GET | `/roles/create` | `roles.create` | `RoleController@create` | Crear rol |
| POST | `/roles` | `roles.store` | `RoleController@store` | Almacenar rol |
| GET | `/roles/{id}` | `roles.show` | `RoleController@show` | Ver rol |
| GET | `/roles/{id}/edit` | `roles.edit` | `RoleController@edit` | Editar rol |
| PUT | `/roles/{id}` | `roles.update` | `RoleController@update` | Actualizar rol |
| DELETE | `/roles/{id}` | `roles.destroy` | `RoleController@destroy` | Eliminar rol |
| GET | `/permisos` | `permisos.index` | `PermissionController@index` | Lista de permisos |
| GET | `/permisos/create` | `permisos.create` | `PermissionController@create` | Crear permiso |
| POST | `/permisos` | `permisos.store` | `PermissionController@store` | Almacenar permiso |
| GET | `/permisos/{id}` | `permisos.show` | `PermissionController@show` | Ver permiso |
| GET | `/permisos/{id}/edit` | `permisos.edit` | `PermissionController@edit` | Editar permiso |
| PUT | `/permisos/{id}` | `permisos.update` | `PermissionController@update` | Actualizar permiso |
| DELETE | `/permisos/{id}` | `permisos.destroy` | `PermissionController@destroy` | Eliminar permiso |
| POST | `/permisos/update` | `permisos.update` | `PermissionController@update` | Actualización de permisos (POST) |

### 4.7 Reportes y auditoría

| Método | URL | Nombre | Controlador@Método | Descripción |
|--------|-----|--------|-------------------|------------|
| GET | `/movilidad` | `movilidad.reporte` | `MovilidadReporteController@index` | Dashboard de movilidad |
| GET | `/movilidad-reporte/rutas` | — | `MovilidadReporteController@rutasPorFecha` | Rutas agrupadas por fecha |
| GET | `/movilidad-reporte/movilidades` | — | `MovilidadReporteController@movilidadesPorRuta` | Vehículos por ruta |
| GET | `/movilidad-reporte/manifiesto` | — | `MovilidadReporteController@manifiestoPorMovilidad` | Manifiesto de pasajeros por vehículo |
| GET | `/logs` | `logs.index` | `ActivityLogController@index` | Auditoría de actividades (Spatie) |

## V. Grupos de rutas identificados

### 5.1 Grupo de rutas públicas
- Propósito: Permitir que clientes no autenticados vean tours y realicen reservas
- Rutas: `home`, `blog.web`, `rutas.tipo`, `rutas.descripcion`, `reserva.formulario`, `reservas.store`
- Seguridad: Solo CSRF (global de Laravel)

### 5.2 Grupo de rutas de pago
- Propósito: Integración con MercadoPago
- Rutas: `mercadopago.checkout`, `mercadopago.success`, `mercadopago.failure`
- Seguridad: **CRÍTICA** - No validadas, vulnerables a manipulación

### 5.3 Grupo de rutas protegidas (autenticadas)
- Propósito: Gestión administrativa de tours, reservas, clientes, pagos
- Protección: `auth:sanctum`, `jetstream.auth_session`, `verified`
- Rutas: Todos los resource controllers (rutas, clientes, guías, etc.)
- Nota: No se detectan permisos granulares en las rutas, solo autenticación

## VI. Flujo de navegación principal

```
┌─────────────────────────────────────────────────────────────┐
│                    USUARIO INVITADO                        │
└─────────────────────────────────────────────────────────────┘
           │
           ▼
    GET / (home)
    ┌─────────────────┐
    │ Página principal│
    │ Tours destacados│
    └─────────────────┘
           │
           ├─────────────────────────┬──────────────────────────┐
           │                         │                          │
           ▼                         ▼                          ▼
    GET /blog              GET /rutas/tipo/{tipo}        GET /rutas/{id}/descripcion
    ┌──────────┐           ┌─────────────────────┐      ┌──────────────────┐
    │  Blog    │           │ Tours por categoría │      │ Detalles completos│
    │ Artículos│           │ Listado detallado   │      │ Fechas disponibles│
    └──────────┘           └─────────────────────┘      └──────────────────┘
                                                                │
                                                                ▼
                                                      GET /reserva/{ruta}
                                                      ┌──────────────────┐
                                                      │ Formulario reserva│
                                                      │ Ingresa datos     │
                                                      └──────────────────┘
                                                                │
                                                                ▼
                                                      POST /reserva
                                                      ┌──────────────────┐
                                                      │ Valida formulario │
                                                      │ Almacena sesión   │
                                                      └──────────────────┘
                                                                │
                                                                ▼
                                                      POST /checkout
                                                      ┌──────────────────┐
                                                      │ Generapreferencia│
                                                      │ MercadoPago       │
                                                      └──────────────────┘
                                                                │
                                                                ▼
                                                      Redirect MercadoPago
                                                      ┌──────────────────┐
                                                      │ Usuario paga      │
                                                      └──────────────────┘
                                                                │
                                ┌───────────────────────────────┼───────────────────────────────┐
                                │                               │                               │
                                ▼                               ▼                               ▼
                        GET /mercadopago/success      GET /mercadopago/failure      (timeout/cancel)
                        ┌──────────────────────┐      ┌──────────────────────┐
                        │ Validar pago         │      │ Mostrar error        │
                        │ Crear Reserva + Pago │      │ Permitir reintentar  │
                        │ Email confirmación   │      └──────────────────────┘
                        │ Redireccionar        │
                        └──────────────────────┘

┌─────────────────────────────────────────────────────────────┐
│                    USUARIO ADMINISTRADOR                    │
└─────────────────────────────────────────────────────────────┘
           │
           └─► Jetstream Login ──┐
                                 ▼
                         GET /dashboard
                    (DashboardController@index)
                    ┌────────────────────┐
                    │ KPIs y gráficos    │
                    │ Menú administrativo│
                    └────────────────────┘
                             │
         ┌───────────────────┼───────────────────┬──────────────┬─────────────────┐
         │                   │                   │              │                 │
         ▼                   ▼                   ▼              ▼                 ▼
    GET /rutas        GET /gestionreservas  GET /clientes   GET /guias    GET /pagos
    ┌─────────────┐   ┌──────────────────┐  ┌──────────┐   ┌─────────┐   ┌─────────┐
    │ CRUD Tours  │   │ CRUD Reservas    │  │ CRUD     │   │ CRUD    │   │ CRUD    │
    │ + Detalles  │   │ + Búsqueda DNI   │  │ Clientes │   │ Guías   │   │ Pagos   │
    │ + Fechas    │   │ + Vehículos      │  └──────────┘   └─────────┘   └─────────┘
    │ + Lugares   │   └──────────────────┘
    │ + Servicios │
    │ + Imágenes  │
    └─────────────┘
         │
         ▼
    GET /movilidad
    ┌────────────────────────────────┐
    │ Reportes de movilidad          │
    │ - Rutas por fecha              │
    │ - Vehículos por ruta           │
    │ - Manifiesto por vehículo      │
    └────────────────────────────────┘
         │
         ▼
    GET /logs
    ┌────────────────────────────────┐
    │ Auditoría de cambios (Spatie)  │
    │ Historial de acciones          │
    └────────────────────────────────┘
```

## VII. Auditoría de errores

### 7.1 Rutas duplicadas

**HALLAZGO**: Se detectan dos rutas alternativas para listar reservas:
- `GET /gestionreservas` → `ReservaController@index`
- `GET /listareservas` → `ListarReservasController@index`

Ambas parecen tener el mismo propósito pero usan controladores diferentes. Esto es redundante y confuso.

### 7.2 Rutas sin protección de seguridad

| Ruta | Controlador | Riesgo | Recomendación |
|------|-------------|--------|---------------|
| `POST /reserva` | ReservaClienteController@store | Spam de reservas no validadas | Implementar rate limiting y validación stricter |
| `POST /checkout` | MercadoPagoController@checkout | Manipulación de precio/cantidad | Validar contra BD, no confiar en cliente |
| `GET /mercadopago/success` | MercadoPagoController@success | Pago sin validar firma MP | **CRÍTICA**: Validar firma de MercadoPago |
| `GET /mercadopago/failure` | MercadoPagoController@failure | Acceso directo sin pago real | Validar estado de pago en BD |

### 7.3 Controladores y métodos verificados

✅ Todos los controladores mencionados existen en `app/Http/Controllers/`:

- HomeController
- ReservaClienteController
- RutaController
- DetalleRutaController
- FechaDisponibleController
- LugarVisitarController
- ServicioIncluidoController
- ImagenController
- ClienteController
- GuiaController
- MovilidadController
- ReservaMovilidadController
- PagoController
- ReservaController
- ListarReservasController
- MercadoPagoController
- RoleController
- PermissionController
- DashboardController
- MovilidadReporteController
- ActivityLogController (Admin)
- UsuarioController

### 7.4 Métodos faltantes en rutas

Los siguientes recursos utilizan el patrón RESTful estándar que incluye los métodos:
- `index`, `create`, `store`, `show`, `edit`, `update`, `destroy`

Sin embargo, algunos controladores tienen métodos adicionales no incluidos en `resource()`:
- `ReservaController@buscarPorDNI` (POST)
- `ListarReservasController@buscarPorDocumento` (GET)
- `ListarReservasController@obtenerFechasPorRuta` (GET)
- `MovilidadReporteController@rutasPorFecha` (GET)
- `MovilidadReporteController@movilidadesPorRuta` (GET)
- `MovilidadReporteController@manifiestoPorMovilidad` (GET)
- `ActivityLogController@index` (GET)

Estos métodos no RESTful están correctamente registrados como rutas individuales.

### 7.5 Problemas de permisos granulares

**HALLAZGO**: Las rutas protegidas usan `auth`, `verified`, pero NO implementan permisos granulares.

Según `database.md`, Spatie Permission está configurado, pero en las rutas no se ve:
```php
Route::middleware('can:rutas.ver')->group(...)
```

Esto significa que cualquier usuario autenticado y verificado puede acceder a TODAS las rutas administrativas, lo que es un riesgo de seguridad grave.

**RECOMENDACIÓN**: Agregar middleware de permisos por ruta o grupo.

### 7.6 Rutas faltantes

Comparando con los controladores existentes en `app/Http/Controllers/`:
- `ReniecController` existe pero no tiene rutas registradas (validación de DNI Perú)
- Posiblemente se usa internamente sin ruta web pública

## VIII. Resumen de seguridad

| Categoría | Estado | Notas |
|-----------|--------|-------|
| Autenticación (rutas protegidas) | ✅ Bien | Jetstream + Sanctum correctamente configurados |
| Email verification | ✅ Bien | Requerido para rutas protegidas |
| Rate limiting | ⚠️ Falta | No visible en rutas públicas (POST /reserva, /checkout) |
| Permisos granulares | ❌ Falta | Todas las rutas autenticadas sin verificación de permisos |
| Validación de MercadoPago | ❌ CRÍTICA | Callbacks sin validación de firma |
| CSRF | ✅ Bien | Incluido globalmente en middleware |
| SQL Injection | ✅ Bien | Usando Eloquent ORM, no SQL directo visible |
| Validación de entrada | ? Desconocido | Requiere revisar controladores y FormRequests |

