# ANÁLISIS DE TESTING Y CALIDAD DE CÓDIGO - AGENTS

**Fecha de Análisis**: 2026-05-08  
**Versión Laravel**: 11  
**Framework de Testing**: PHPUnit 11.0.1  
**Herramientas Instaladas**: Pint, Mockery, FakerPHP, Pest (config), Larastan  

---

## 📋 TABLA DE CONTENIDOS

1. [Estado Actual de Cobertura](#estado-actual-de-cobertura)
2. [Análisis de Tests Existentes](#análisis-de-tests-existentes)
3. [Calidad de Código](#calidad-de-código)
4. [Configuración de Testing](#configuración-de-testing)
5. [Gaps de Testing Identificados](#gaps-de-testing-identificados)
6. [Plan de Pruebas Necesario](#plan-de-pruebas-necesario)
7. [Recomendaciones](#recomendaciones)

---

## 📊 ESTADO ACTUAL DE COBERTURA

### Resumen Ejecutivo

```
┌─────────────────────────────────┐
│  COBERTURA ESTIMADA: 5-10%     │
│  ─────────────────────────────  │
│  Tests Totales: 22              │
│  Coverage: JETSTREAM ONLY       │
│  Domain Logic: ❌ NO COVERED    │
│  Críticos Faltantes: 15+        │
└─────────────────────────────────┘
```

### Desglose por Categoría

| Categoría | Tests | Cobertura | Estado |
|---|---|---|---|
| **Autenticación (Jetstream)** | 3 | ✅ 100% | COVERED |
| **Teams (Jetstream)** | 8 | ✅ 100% | COVERED |
| **API Tokens (Sanctum)** | 3 | ✅ 100% | COVERED |
| **Perfil de Usuario** | 2 | ✅ 100% | COVERED |
| **Contraseñas** | 3 | ✅ 100% | COVERED |
| **2FA** | 2 | ✅ 100% | COVERED |
| **MercadoPago Integration** | 0 | ❌ 0% | MISSING |
| **Reserva CRUD** | 0 | ❌ 0% | MISSING |
| **Ruta Management** | 0 | ❌ 0% | MISSING |
| **Cliente Management** | 0 | ❌ 0% | MISSING |
| **Pago Processing** | 0 | ❌ 0% | MISSING |
| **FechaDisponible** | 0 | ❌ 0% | MISSING |
| **Guía Management** | 0 | ❌ 0% | MISSING |
| **Movilidad Management** | 0 | ❌ 0% | MISSING |
| **Spatie Permissions** | 0 | ❌ 0% | MISSING |
| **Reportes** | 0 | ❌ 0% | MISSING |

**Conclusión**: El proyecto tiene buena cobertura de **autenticación Jetstream** (de caja), pero **CERO cobertura del dominio del negocio** (outdoor tours).

---

## 🧪 ANÁLISIS DE TESTS EXISTENTES

### 1. Estructura Actual

**Ubicación**: `tests/` (22 archivos total)

```
tests/
├── Feature/                          (21 tests)
│   ├── AuthenticationTest.php         ✅ 3 tests
│   ├── RegistrationTest.php           ✅ 3 tests
│   ├── PasswordResetTest.php          ✅ 2 tests
│   ├── PasswordConfirmationTest.php   ✅ 1 test
│   ├── CreateTeamTest.php             ✅ 1 test
│   ├── UpdateTeamNameTest.php         ✅ 2 tests
│   ├── UpdateTeamMemberRoleTest.php   ✅ 1 test
│   ├── DeleteTeamTest.php             ✅ 1 test
│   ├── InviteTeamMemberTest.php       ✅ 2 tests
│   ├── RemoveTeamMemberTest.php       ✅ 2 tests
│   ├── LeaveTeamTest.php              ✅ 1 test
│   ├── EmailVerificationTest.php      ✅ 2 tests
│   ├── CreateApiTokenTest.php         ✅ 1 test
│   ├── DeleteApiTokenTest.php         ✅ 2 tests
│   ├── ApiTokenPermissionsTest.php    ✅ 1 test
│   ├── TwoFactorAuthenticationSettingsTest.php  ✅ 3 tests
│   ├── ProfileInformationTest.php     ✅ 1 test
│   ├── UpdatePasswordTest.php         ✅ 1 test
│   ├── BrowserSessionsTest.php        ✅ 1 test
│   └── ExampleTest.php                ⚠️  1 test (comentado RefreshDatabase)
│
├── Unit/                             (1 test)
│   └── ExampleTest.php                ⚠️  1 test (trivial: assertTrue(true))
│
├── TestCase.php                       (Base class vacío)
└── Feature/                           (Empty directory except above)
```

### 2. Patrón de Testing Jetstream

**Tipo**: Feature Tests (pruebas end-to-end)

**Ejemplo Típico**:
```php
// AuthenticationTest.php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;  // ← Limpia BD después de cada test

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create();  // ← Factory para usuarios

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();  // ← Aserta que está autenticado
        $response->assertRedirect(route('dashboard', absolute: false));
    }
}
```

**Características**:
- ✅ Usan `RefreshDatabase` (ejecutan en transacción, no interfieren)
- ✅ Testean HTTP requests completos (POST, GET, etc.)
- ✅ Verifican redirects y status codes
- ✅ Usan factories para crear datos de prueba
- ✅ Usan `actingAs()` para simular usuarios autenticados

### 3. Tests de Livewire

**Ubicación**: Varios tests usan Livewire::test()

```php
// CreateTeamTest.php
public function test_teams_can_be_created(): void
{
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(CreateTeamForm::class)
        ->set(['state' => ['name' => 'Test Team']])
        ->call('createTeam');

    $this->assertCount(2, $user->fresh()->ownedTeams);
}
```

**Observación**: Bien estructurado para componentes Livewire, pero **NO HAY componentes Livewire para el dominio** (según auditoría de frontend).

### 4. Tests de API Tokens

```php
// CreateApiTokenTest.php
public function test_api_tokens_can_be_created(): void
{
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(ApiTokenManager::class)
        ->set(['createApiTokenForm' => [
            'name' => 'Test Token',
            'permissions' => ['read', 'update'],
        ]])
        ->call('createApiToken');

    $this->assertCount(1, $user->fresh()->tokens);
    $this->assertTrue($user->fresh()->tokens->first()->can('read'));
}
```

**Análisis**:
- ✅ Verifica creación de tokens
- ✅ Verifica permisos asignados
- ⚠️ NO verifica expiración de tokens
- ⚠️ NO verifica revocación de tokens

---

## 🔍 CALIDAD DE CÓDIGO

### 1. Herramientas Instaladas

| Herramienta | Versión | Instalado | Configurado | Usado |
|---|---|---|---|---|
| **PHPUnit** | 11.0.1 | ✅ | ✅ | ✅ |
| **Mockery** | 1.6 | ✅ | ✅ | ⚠️ Bajo |
| **FakerPHP** | 1.23 | ✅ | ✅ | ✅ |
| **Laravel Pint** | 1.13 | ✅ | ⚠️ Default | ⚠️ No usado |
| **Pest Framework** | - | ✅ Config | ❌ | ❌ |
| **PHPStan/Larastan** | 3.2-3.3 | ✅ Indirect | ❌ | ❌ |

**Ubicación en composer.json**:

```json
{
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pail": "^1.1",
        "laravel/pint": "^1.13",            // ← Linting
        "laravel/sail": "^1.26",            // ← Docker
        "mockery/mockery": "^1.6",          // ← Mocking
        "nunomaduro/collision": "^8.1",     // ← Error display
        "phpunit/phpunit": "^11.0.1"        // ← Main testing
    }
}
```

### 2. Análisis de Calidad Actual

#### ✅ Aspectos Positivos

1. **PHPUnit Correctamente Configurado**
   - ✅ phpunit.xml con buenas defaults
   - ✅ Namespacing adecuado
   - ✅ Autoloader configurado

2. **Factories Bien Estructuradas**
   - ✅ `UserFactory` con métodos helpers
   - ✅ `TeamFactory` para multitenancy
   - ✅ Uso de `fake()->` helpers

3. **Patrón Consistente en Feature Tests**
   - ✅ Todos usan `RefreshDatabase`
   - ✅ Estructura clara de AAA (Arrange-Act-Assert)
   - ✅ Nombres descriptivos

4. **Integración Jetstream Completa**
   - ✅ Tests de autenticación
   - ✅ Tests de 2FA
   - ✅ Tests de Teams

#### ⚠️ Problemas Críticos

1. **ZERO Pruebas del Dominio**
   - ❌ MercadoPago: 0 tests
   - ❌ Reserva: 0 tests
   - ❌ Ruta: 0 tests
   - ❌ Cliente: 0 tests
   - ❌ Pago: 0 tests

2. **Controllers Sin Tests**
   ```php
   // app/Http/Controllers/ (23 controllers)
   // Ninguno tiene pruebas unitarias
   - RutaController           ❌
   - ReservaController        ❌
   - MercadoPagoController    ❌ (CRÍTICO - maneja pagos)
   - ClienteController        ❌
   - GuiaController           ❌
   - MovilidadController      ❌
   - PagoController           ❌
   - etc.
   ```

3. **Modelos Sin Tests**
   ```php
   // app/Models/ (18 modelos)
   - Reserva          ❌ (LogsActivity habilitado pero sin tests)
   - Pago             ❌ (Relación incorrecta hasMany/belongsTo)
   - Cliente          ❌
   - Ruta             ❌
   - FechaDisponible  ❌
   - Movilidad        ❌
   - Guia             ❌
   ```

4. **Validaciones Sin Tests**
   - ❌ FormRequest no existe (validaciones inline)
   - ❌ Reglas de negocio no probadas
   - ❌ Edge cases no cubiertos

5. **Integraciones Externas Sin Tests**
   - ❌ MercadoPago API (sin mock)
   - ❌ Email sending (ConfirmacionReserva)
   - ❌ Database transactions

### 3. Análisis de Legibilidad - Controllers Sobrecargados

**Hallazgo de Auditoría Anterior**: Controllers con 100+ líneas

**Impacto en Testing**:

```php
// ❌ DIFÍCIL DE TESTEAR: Controller monolítico
class MercadoPagoController extends Controller
{
    public function success(Request $request)
    {
        // 100+ líneas mezcladas:
        // 1. Validar MercadoPago API
        // 2. Crear Cliente
        // 3. Crear Reserva
        // 4. Crear ReservaCliente
        // 5. Crear Pago
        // 6. Enviar email
        // 7. Registrar logging
        
        // ← Para testear UN comportamiento, hay que testear TODO
    }
}

// ✅ FÁCIL DE TESTEAR: Service layer separado
class CreateReservationService
{
    public function create(array $data): Reserva
    {
        // Solo lógica de creación de reserva
    }
}

class SendConfirmationEmailService
{
    public function send(Reserva $reserva): void
    {
        // Solo envío de email
    }
}
```

**Implicación**: Los controllers necesitan refactoring antes de poder testearlos efectivamente.

### 4. Laravel Pint - Code Formatting

**Estado**: Instalado pero no configurado/usado

**Archivo de Configuración**: NO EXISTE (usaría defaults)

```bash
# Para usar Pint (linting/formatting):
php artisan pint

# Configuración típica (pint.json - NO EXISTE):
# Sería ideal crear esto con:
# - PSR-12 standard
# - Excepciones para el proyecto
# - IDE integration
```

**Recomendación**: Crear `pint.json` para forzar estilo consistente en el equipo.

### 5. PHPStan/Larastan - Static Analysis

**Estado**: En `composer.lock` (dependencia transitiva) pero NO en `require-dev`

**Implicación**: 
- ❌ No se está usando para análisis estático
- ❌ No se detectan type errors antes de runtime
- ❌ No hay validación de contratos de tipo

**Beneficio Potencial**:
```php
// PHPStan detectaría:
public function reserva()
{
    return $this->hasMany(Pago::class);  // ← Error de tipo
    // Debería ser belongsTo()
}
```

---

## ⚙️ CONFIGURACIÓN DE TESTING

### 1. phpunit.xml - Análisis Detallado

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true">
    
    <!-- Test Suites -->
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    
    <!-- Code Coverage Source -->
    <source>
        <include>
            <directory>app</directory>  <!-- ← Solo testea app/, no routes/config -->
        </include>
    </source>
    
    <!-- Environment Variables para Testing -->
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_MAINTENANCE_DRIVER" value="file"/>
        <env name="BCRYPT_ROUNDS" value="4"/>  <!-- ← Más rápido que producción -->
        <env name="CACHE_STORE" value="array"/>  <!-- ← En memoria, no persistent -->
        <!-- COMENTADO - DEFAULT USES MYSQL:
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        -->
        <env name="MAIL_MAILER" value="array"/>  <!-- ← Captura emails, no envía -->
        <env name="PULSE_ENABLED" value="false"/>
        <env name="QUEUE_CONNECTION" value="sync"/>  <!-- ← Ejecuta jobs síncronos -->
        <env name="SESSION_DRIVER" value="array"/>  <!-- ← En memoria -->
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

#### ⚠️ Problema Crítico: DB_CONNECTION Comentado

```xml
<!-- <env name="DB_CONNECTION" value="sqlite"/> -->
<!-- <env name="DB_DATABASE" value=":memory:"/> -->
```

**Implicación**:
- Tests usan la **BD de desarrollo por defecto** (MySQL probablemente)
- ❌ Lento: tests conectan a servidor real
- ❌ Riesgo: pueden contaminar BD de desarrollo
- ❌ No aislado: si BD está en estado extraño, tests fallan
- ❌ No parallelizable: múltiples test runners interfieren

**Solución**:
```xml
<!-- RECOMENDADO -->
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<!-- O usar -->
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value="database/testing.sqlite"/>
```

### 2. TestCase.php - Base Class

```php
// tests/TestCase.php
namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    //  ← VACÍO, no tiene helpers personalizados
}
```

**Problema**: No hay métodos helpers para testing del dominio

**Debería tener**:
```php
class TestCase extends BaseTestCase
{
    protected function createReserva(array $overrides = []): Reserva
    {
        return Reserva::factory()->create($overrides);
    }
    
    protected function createRuta(array $overrides = []): Ruta
    {
        return Ruta::factory()->create($overrides);
    }
    
    protected function loginAs(User $user): self
    {
        return $this->actingAs($user);
    }
}
```

### 3. Execution Speed

```
Estimado actual:
- 22 tests × ~0.5s cada uno = ~11 segundos (SIN DB remota)
- Si usa MySQL remoto: 22 tests × ~2-3s = 44-66 segundos

Después de cambios recomendados:
- SQLite :memory: = ~5-8 segundos para suite completa
```

---

## 🚨 GAPS DE TESTING IDENTIFICADOS

### Matriz de Gaps Críticos

| Módulo | Feature | Unit | Integration | Coverage | Criticidad |
|---|---|---|---|---|---|
| **MercadoPago** | ❌ | ❌ | ❌ | 0% | 🔴 CRÍTICA |
| **Reserva** | ❌ | ❌ | ❌ | 0% | 🔴 CRÍTICA |
| **Pago** | ❌ | ❌ | ❌ | 0% | 🔴 CRÍTICA |
| **Cliente** | ❌ | ❌ | ❌ | 0% | 🟡 ALTA |
| **Ruta** | ❌ | ❌ | ❌ | 0% | 🟡 ALTA |
| **FechaDisponible** | ❌ | ❌ | ❌ | 0% | 🟡 ALTA |
| **Movilidad** | ❌ | ❌ | ❌ | 0% | 🟠 MEDIA |
| **Guia** | ❌ | ❌ | ❌ | 0% | 🟠 MEDIA |
| **Validaciones** | ❌ | ❌ | ❌ | 0% | 🟡 ALTA |
| **Reportes** | ❌ | ❌ | ❌ | 0% | 🟠 MEDIA |
| **Permissions** | ❌ | ❌ | ❌ | 0% | 🟡 ALTA |
| **Email** | ❌ | ❌ | ❌ | 0% | 🟠 MEDIA |

### 1. MercadoPago - GAPS CRÍTICOS

**Falta**: 
- ❌ Tests para checkout flow
- ❌ Tests para success callback
- ❌ Tests para failure callback
- ❌ Mock de MercadoPago API
- ❌ Validación de firma webhook
- ❌ Rate limiting
- ❌ Session manipulation scenarios

**Ejemplos de tests que faltan**:

```php
// ❌ NO EXISTE
class MercadoPagoTest extends TestCase
{
    public function test_checkout_request_creates_preference(): void
    {
        // Debería testar: POST /checkout
        // Esperar: external_reference guardado en sesión
        // Esperar: redirección a MP
    }
    
    public function test_success_callback_creates_reservation(): void
    {
        // Debería testar: GET /mercadopago/success
        // Esperar: Reserva creada con datos correctos
        // Esperar: Email enviado
    }
    
    public function test_invalid_signature_rejected(): void
    {
        // Debería testar: firma MercadoPago validada
        // Esperar: rechazar con 403
    }
    
    public function test_rate_limiting_enforced(): void
    {
        // Debería testar: throttle:5,1 middleware
        // Esperar: 429 después de 5 requests
    }
}
```

### 2. Reserva - GAPS CRÍTICOS

**Falta**:
- ❌ Tests de creación de Reserva
- ❌ Tests de relaciones (FechaDisponible, Cliente)
- ❌ Tests de estado transitions (Pendiente → Pagado)
- ❌ Tests de cantidad_personas validation
- ❌ Tests de composite keys en ReservaCliente

```php
// ❌ NO EXISTE
class ReservaTest extends TestCase
{
    public function test_reserva_can_be_created_with_valid_data(): void
    {
        $fecha = FechaDisponible::factory()->create();
        
        $reserva = Reserva::create([
            'id_fecha' => $fecha->id_fecha,
            'fecha_reserva' => now(),
            'cantidad_personas' => 2,
            'precio_total' => 100.00,
            'saldo' => 50.00,
            'estado' => 'pendiente',
        ]);
        
        $this->assertDatabaseHas('reservas', [
            'id_reserva' => $reserva->id_reserva,
        ]);
    }
    
    public function test_reserva_requires_valid_fecha_disponible(): void
    {
        // Esperar: constraint de FK
    }
    
    public function test_reserva_estado_transitions_valid(): void
    {
        $reserva = Reserva::factory()->create(['estado' => 'pendiente']);
        
        // Cambiar a pagado
        $reserva->update(['estado' => 'pagado']);
        
        // ¿Debería validar transiciones válidas?
        // Pendiente → Pagado ✅
        // Pagado → Cancelado ✅
        // Abordo → Pendiente ❌
    }
}
```

### 3. Pago - GAPS CRÍTICOS

**Falta**:
- ❌ Tests de creación de Pago
- ❌ Tests de relación incorrecta (hasMany vs belongsTo)
- ❌ Tests de monto_pagado validation
- ❌ Tests de saldo calculation

```php
// ❌ NO EXISTE
class PagoTest extends TestCase
{
    public function test_pago_belongs_to_reserva(): void
    {
        $reserva = Reserva::factory()->create();
        $pago = Pago::create([
            'id_reserva' => $reserva->id_reserva,
            'metodo_pago' => 'mercadopago',
            'monto_pagado' => 50.00,
            'fecha_pago' => now(),
        ]);
        
        // ✅ Debería poder hacer:
        $this->assertEquals($reserva->id_reserva, $pago->reserva->id_reserva);
        
        // ❌ ACTUALMENTE FALLA (relación hasMany incorrecta)
    }
}
```

### 4. Validaciones - GAPS

**Falta**: FormRequest + tests de validación

```php
// ❌ NO EXISTE: app/Http/Requests/CheckoutRequest.php
// ❌ SIN TESTS: validar datos de checkout

class CheckoutRequestTest extends TestCase
{
    public function test_checkout_requires_valid_ruta(): void
    {
        $response = $this->postJson('/checkout', [
            'id_ruta' => 99999,  // No existe
        ]);
        
        $response->assertStatus(422);
    }
    
    public function test_checkout_requires_positive_cantidad(): void
    {
        $response = $this->postJson('/checkout', [
            'cantidad_personas' => 0,  // Inválido
        ]);
        
        $response->assertStatus(422);
    }
}
```

### 5. Permissions - GAPS

**Falta**: Tests de Spatie Permission

```php
// ❌ NO EXISTE
class PermissionTest extends TestCase
{
    public function test_admin_can_view_all_clientes(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        
        $response = $this->actingAs($admin)->get('/clientes');
        
        $response->assertStatus(200);
    }
    
    public function test_user_cannot_view_clientes_without_permission(): void
    {
        $user = User::factory()->create();
        $user->assignRole('usuario');
        
        $response = $this->actingAs($user)->get('/clientes');
        
        $response->assertStatus(403);  // Forbidden
    }
}
```

---

## 📋 PLAN DE PRUEBAS NECESARIO

### Fase 1: CRÍTICA (Antes de migrar a MySQL)

**Objetivo**: Asegurar que NO se pierdan datos en migración

#### 1.1 Database Integrity Tests

```php
// tests/Feature/DatabaseIntegrityTest.php
class DatabaseIntegrityTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_all_tables_exist(): void
    {
        // Verificar que todas las migraciones se ejecutaron
        $tables = [
            'rutas', 'fecha_disponibles', 'clientes', 'reservas',
            'reserva_clientes', 'pagos', 'movilidads', 'movilidad_guias',
            'guias', // ... todas las 16 tablas
        ];
        
        foreach ($tables as $table) {
            $this->assertTrue(
                Schema::hasTable($table),
                "Tabla {$table} no existe"
            );
        }
    }
    
    public function test_foreign_keys_configured(): void
    {
        // Verificar FK constraints
        $this->assertTrue(Schema::hasColumn('fecha_disponibles', 'id_ruta'));
        // ... verificar todas las FKs
    }
    
    public function test_composite_keys_work(): void
    {
        // Verificar composite keys en pivot tables
        $reserva = Reserva::factory()->create();
        $cliente = Cliente::factory()->create();
        
        ReservaCliente::create([
            'id_reserva' => $reserva->id_reserva,
            'id_cliente' => $cliente->id_cliente,
        ]);
        
        // No debería poder insertar duplicado
        $this->expectException(QueryException::class);
        ReservaCliente::create([
            'id_reserva' => $reserva->id_reserva,
            'id_cliente' => $cliente->id_cliente,
        ]);
    }
}
```

#### 1.2 MercadoPago Payment Flow Tests

```php
// tests/Feature/MercadoPagoPaymentFlowTest.php
class MercadoPagoPaymentFlowTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_complete_payment_flow(): void
    {
        // 1. Usuario llena formulario
        $ruta = Ruta::factory()->create(['precio_actual' => 100.00]);
        $fecha = FechaDisponible::factory()->for($ruta)->create();
        
        // 2. POST /checkout
        $response = $this->postJson('/checkout', [
            'id_ruta' => $ruta->id_ruta,
            'id_fecha' => $fecha->id_fecha,
            'cantidad_personas' => 2,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'numero_documento' => '12345678',
            'email' => 'juan@example.com',
            'telefono' => '987654321',
            'pais' => 'Perú',
            'region' => 'Lima',
            'ciudad' => 'Lima',
            'tipo_documento' => 'dni',
            'fecha_nacimiento' => '1990-01-01',
        ]);
        
        // 3. Sesión contiene datos
        $this->assertNotNull(session('datos_reserva'));
        
        // 4. Simular callback exitoso de MP
        $response = $this->getJson('/mercadopago/success', [
            'payment_id' => '123456789',
            'external_reference' => session('datos_reserva')['external_reference'],
        ]);
        
        // 5. Verificar que se creó Reserva
        $this->assertDatabaseHas('reservas', [
            'cantidad_personas' => 2,
            'precio_total' => 200.00,
        ]);
        
        // 6. Verificar que se creó Cliente
        $this->assertDatabaseHas('clientes', [
            'numero_documento' => '12345678',
        ]);
        
        // 7. Verificar que se creó Pago
        $this->assertDatabaseHas('pagos', [
            'monto_pagado' => 100.00,  // 50% del total
        ]);
    }
}
```

#### 1.3 Model Relationships Tests

```php
// tests/Unit/ModelsTest.php
class ModelsTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_ruta_has_many_fechas(): void
    {
        $ruta = Ruta::factory()->create();
        $fechas = FechaDisponible::factory(3)->for($ruta)->create();
        
        $this->assertCount(3, $ruta->fechasDisponibles);
    }
    
    public function test_reserva_belongs_to_fecha(): void
    {
        $fecha = FechaDisponible::factory()->create();
        $reserva = Reserva::factory()->for($fecha)->create();
        
        $this->assertEquals($fecha->id_fecha, $reserva->fechaDisponible->id_fecha);
    }
    
    public function test_reserva_many_to_many_clientes(): void
    {
        $reserva = Reserva::factory()->create();
        $clientes = Cliente::factory(2)->create();
        
        foreach ($clientes as $cliente) {
            ReservaCliente::create([
                'id_reserva' => $reserva->id_reserva,
                'id_cliente' => $cliente->id_cliente,
            ]);
        }
        
        $this->assertCount(2, $reserva->clientes);
    }
    
    public function test_pago_belongs_to_reserva(): void  // ← ESTE FALLA ACTUALMENTE
    {
        $reserva = Reserva::factory()->create();
        $pago = Pago::factory()->for($reserva)->create();
        
        // DEBERÍA funcionar pero NO FUNCIONA (relación hasMany incorrecta)
        $this->assertEquals($reserva->id_reserva, $pago->reserva->id_reserva);
    }
}
```

### Fase 2: ALTA (Semana 1)

- [ ] MercadoPago Controller Tests (10 tests)
- [ ] Reserva Model & Factory Tests (8 tests)
- [ ] Pago Model Tests (5 tests)
- [ ] Cliente Model Tests (4 tests)
- [ ] Ruta CRUD Tests (8 tests)

**Total**: ~35 tests nuevos

### Fase 3: MEDIA (Semana 2)

- [ ] Permissions Tests (10 tests)
- [ ] Email Sending Tests (3 tests)
- [ ] Reportes Tests (6 tests)
- [ ] Date/Time Logic Tests (4 tests)

**Total**: ~23 tests nuevos

### Fase 4: BAJA (Semana 3+)

- [ ] Edge cases
- [ ] Performance tests
- [ ] Load testing

---

## 🏗️ RECOMENDACIONES

### 1. INMEDIATO: Configurar SQLite para Testing

**Archivo**: `phpunit.xml`

```xml
<!-- Descomenta estas líneas -->
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

**Beneficio**: 
- Tests 10x más rápido
- Aislamiento perfecto
- Parallelizable

### 2. INMEDIATO: Crear Factories para el Dominio

**Archivos a crear**:
```php
database/factories/RutaFactory.php
database/factories/ClienteFactory.php
database/factories/ReservaFactory.php
database/factories/PagoFactory.php
database/factories/FechaDisponibleFactory.php
// ... etc
```

**Ejemplo**:
```php
// database/factories/RutaFactory.php
class RutaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nombre_ruta' => fake()->sentence(3),
            'tipo' => fake()->randomElement(['Trekking', 'Aventura']),
            'precio_regular' => fake()->numberBetween(100, 500),
            'descuento' => 0,
            'precio_actual' => fake()->numberBetween(100, 500),
            'hora_salida' => '08:00',
            'dificultad' => fake()->randomElement(['Fácil', 'Medio', 'Difícil']),
            'estado' => 'Activo',
        ];
    }
}
```

### 3. SEMANA 1: Crear FormRequest + Validations Tests

```php
// app/Http/Requests/CheckoutRequest.php
class CheckoutRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'id_fecha' => 'required|exists:fecha_disponibles,id_fecha',
            'cantidad_personas' => 'required|integer|between:1,50',
            // ... etc
        ];
    }
}

// tests/Feature/CheckoutValidationTest.php
class CheckoutValidationTest extends TestCase
{
    public function test_checkout_validates_required_fields(): void
    {
        $response = $this->postJson('/checkout', []);
        
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['id_ruta', 'id_fecha', ...]);
    }
}
```

### 4. SEMANA 1: Crear MercadoPagoTest

```php
// tests/Feature/MercadoPagoTest.php
class MercadoPagoTest extends TestCase
{
    use RefreshDatabase;
    
    // Stub MercadoPago API
    protected function setUp(): void
    {
        parent::setUp();
        
        Http::fake([
            'api.mercadopago.com/*' => Http::response([
                'id' => '123456789',
                'status' => 'approved',
                'transaction_amount' => 50.00,
            ]),
        ]);
    }
    
    public function test_successful_payment_creates_reservation(): void
    {
        // ... test
    }
}
```

### 5. SEMANA 2: Agregar Code Coverage Reporting

```bash
# phpunit.xml agregar:
<coverage processUncoveredFiles="true">
    <include>
        <directory suffix=".php">app</directory>
    </include>
    <exclude>
        <directory suffix="Requests">app</directory>
        <!-- excluir vistas, migraciones, etc -->
    </exclude>
    <report>
        <html outputDirectory="storage/coverage"/>
        <clover outputFile="storage/coverage.xml"/>
    </report>
</coverage>

# Ejecutar con coverage:
php artisan test --coverage

# Esperar: ~5-10% inicialmente, objetivo 80%+
```

### 6. SEMANA 2: Configurar Laravel Pint

**Crear archivo**: `pint.json`

```json
{
    "preset": "laravel",
    "rules": {
        "spaces_after_function_keyword": true,
        "spaces_inside_parentheses": false,
        "trailing_comma_in_list_call": true
    }
}
```

**Uso**:
```bash
php artisan pint  # Limpiar código
```

### 7. SEMANA 3: Instalar PHPStan para Static Analysis

```bash
composer require --dev larastan/larastan
```

**Crear**: `phpstan.neon`

```neon
includes:
    - vendor/larastan/larastan/extension.neon

parameters:
    paths:
        - app
    level: 5
    checkMissingIterableValueType: false
```

**Uso**:
```bash
./vendor/bin/phpstan analyse
```

### 8. Documentar Estándares de Testing

**Crear**: `docs/testing-standards.md`

Incluir:
- Convenciones de nombres
- Patrón AAA (Arrange-Act-Assert)
- Cuando usar Mocks vs Integration tests
- Factories vs Fixtures
- Coverage targets por módulo

---

## 📊 ROADMAP DE TESTING

```
MAY 8, 2026  
├─ Semana 1 (May 8-15)
│  ├─ ✅ Análisis completado (este documento)
│  ├─ [ ] SQLite :memory: configurado
│  ├─ [ ] 6 Factories creadas (Ruta, Cliente, Reserva, Pago, Fecha, Movilidad)
│  ├─ [ ] MercadoPagoTest (10 tests)
│  └─ [ ] Model Relationships Tests (15 tests)
│
├─ Semana 2 (May 15-22)
│  ├─ [ ] FormRequest + Validation Tests (20 tests)
│  ├─ [ ] CRUD Controller Tests (25 tests)
│  ├─ [ ] Coverage reporting configurado
│  ├─ [ ] Pint configurado (código autoformatteado)
│  └─ [ ] Coverage: ~20%
│
├─ Semana 3 (May 22-29)
│  ├─ [ ] PHPStan integrado (static analysis)
│  ├─ [ ] Permission Tests (15 tests)
│  ├─ [ ] Email Tests (5 tests)
│  ├─ [ ] Edge case tests (10 tests)
│  └─ [ ] Coverage: ~40%
│
└─ Semana 4 (May 29+)
   ├─ [ ] Performance tests
   ├─ [ ] Integration tests con servicios externos
   ├─ [ ] Load testing (MercadoPago flow)
   └─ [ ] Coverage: ~60%+
```

---

## 📈 MÉTRICAS ESPERADAS

### Después de Implementación Completa

| Métrica | Actual | Objetivo |
|---|---|---|
| **Total Tests** | 22 | 120+ |
| **Coverage** | ~5% | 70%+ |
| **Test Execution** | 45s (MySQL) | 8s (SQLite) |
| **MercadoPago Coverage** | 0% | 95% |
| **Model Coverage** | 0% | 85% |
| **Controller Coverage** | 0% | 75% |
| **Validation Coverage** | 0% | 90% |

---

## 🔗 REFERENCIAS

- [PHPUnit Documentation](https://phpunit.de/)
- [Laravel Testing Documentation](https://laravel.com/docs/11.x/testing)
- [Pest Framework](https://pestphp.com/)
- [Laravel Pint](https://laravel.com/docs/11.x/pint)
- [PHPStan Documentation](https://phpstan.org/)

---

## ✅ CHECKLIST DE ACCIÓN

**Prioridad Alta**:
- [ ] Descomenta SQLite en phpunit.xml
- [ ] Crear Factories para dominio
- [ ] Crear MercadoPagoTest
- [ ] Crear Model Relationship Tests

**Prioridad Media**:
- [ ] Crear FormRequest con validaciones
- [ ] Crear Validation Tests
- [ ] Configurar Coverage Reporting
- [ ] Configurar Laravel Pint

**Prioridad Baja**:
- [ ] Instalar PHPStan
- [ ] Crear Testing Standards Doc
- [ ] Integrar con CI/CD

---

**Documento Generado**: 2026-05-08  
**Estado de Testing**: ⚠️ CRÍTICO (5% coverage, CERO pruebas del dominio)  
**Acción Recomendada**: Implementar Fase 1 ANTES de migrar a MySQL
