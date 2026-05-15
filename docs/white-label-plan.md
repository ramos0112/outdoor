# ESTRATEGIA DE TRANSFORMACIÓN: White-Label Multi-Tenant SaaS

**Documento Estratégico**: Plan de Migración y Transformación  
**Fecha**: 2026-05-08  
**Versión**: 1.0  
**Horizonte**: 16 Semanas (4 Fases)  
**Objetivo Final**: Sistema SaaS Multi-empresa con identidad blanca personalizable  

---

## 📋 TABLA DE CONTENIDOS

1. [Visión General](#visión-general)
2. [Arquitectura Multi-Tenant](#arquitectura-multi-tenant)
3. [Estrategia de Migración a MySQL](#estrategia-de-migración-a-mysql)
4. [Infraestructura de Administración](#infraestructura-de-administración)
5. [Seguridad y Procesamiento de Pagos](#seguridad-y-procesamiento-de-pagos)
6. [Hoja de Ruta (Timeline)](#hoja-de-ruta-timeline)
7. [Presupuesto y Recursos](#presupuesto-y-recursos)
8. [Riesgos y Mitigación](#riesgos-y-mitigación)

---

## 🎯 VISIÓN GENERAL

### Estado Actual (2026-05-08)

```
┌─────────────────────────────────────┐
│ SISTEMA MONOLÍTICO SINGLE-TENANT    │
├─────────────────────────────────────┤
│ ✅ Funcionalidad core: Reservas     │
│ ✅ Jetstream auth + 2FA             │
│ ✅ MercadoPago básico               │
│ ⚠️  27 datos "Outdoor Expeditions"  │
│    HARDCODED en frontend            │
│ ❌ CERO tenants multiempresa        │
│ ❌ BD SQLite (dev only)             │
│ ❌ Vulnerabilidades críticas MercadoPago │
└─────────────────────────────────────┘
```

### Estado Objetivo (4 Fases = 16 Semanas)

```
┌──────────────────────────────────────────┐
│ SaaS MULTI-TENANT WHITE-LABEL            │
├──────────────────────────────────────────┤
│ ✅ Mismo código, múltiples empresas      │
│ ✅ Cada empresa: logos, colores, datos   │
│ ✅ BD MySQL producción-ready             │
│ ✅ MercadoPago por empresa (keys únicas) │
│ ✅ Seguridad: validación de firma        │
│ ✅ SuperAdmin panel para gestión         │
│ ✅ Testing: 60%+ cobertura               │
│ ✅ Webhooks y escalabilidad              │
└──────────────────────────────────────────┘
```

### Beneficio Comercial

| Aspecto | Impacto |
|---|---|
| **Ingresos** | 1 cliente → N clientes (replicable) |
| **Costo de Desarrollo** | Código compartido (sin duplicación) |
| **Velocidad de Onboarding** | Empresa nueva en 1 día |
| **Mantenimiento** | Actualizaciones centralizadas |
| **Escalabilidad** | Infraestructura SaaS estándar |

---

## 🏗️ ARQUITECTURA MULTI-TENANT

### 1. Nuevo Modelo: Empresa

**Ubicación**: `app/Models/Empresa.php` (CREAR)

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'empresas';
    protected $primaryKey = 'id_empresa';
    protected $keyType = 'int';
    public $incrementing = true;

    protected $fillable = [
        'nombre_empresa',
        'slug_empresa',              // lowercase-con-guiones (para URL)
        'descripcion',
        'email_contacto',
        'telefono',
        'pais',
        'ciudad',
        'estado',                     // Activo, Inactivo, Suspendido
        'plan',                        // free, profesional, premium
        'limite_usuarios',            // Max usuarios simultaneos
        'fecha_inicio_suscripcion',
        'fecha_fin_suscripcion',
        // Branding
        'logo_url',
        'logo_animation_url',
        'color_primario',             // #dc030c
        'color_secundario',
        'font_family',
        // MercadoPago
        'mercadopago_access_token',   // ENCRIPTADO
        'mercadopago_webhook_secret', // ENCRIPTADO
        // Redes Sociales
        'facebook_url',
        'instagram_url',
        'linkedin_url',
        'whatsapp_numero',
        // Configuración General
        'comision_plataforma',        // % que retiene AGENTS
        'moneda',                      // PEN, USD, etc
        'horario_atencion_inicio',
        'horario_atencion_fin',
        'dias_confirmacion',          // Días antes de viaje para confirmar
    ];

    protected $casts = [
        'fecha_inicio_suscripcion' => 'date',
        'fecha_fin_suscripcion' => 'date',
        'mercadopago_access_token' => 'encrypted',  // ← Encripta automáticamente
        'mercadopago_webhook_secret' => 'encrypted',
    ];

    // ─────────────────────────────────────
    // RELACIONES
    // ─────────────────────────────────────

    public function rutas(): HasMany
    {
        return $this->hasMany(Ruta::class, 'id_empresa', 'id_empresa');
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class, 'id_empresa', 'id_empresa');
    }

    public function reservas(): HasMany
    {
        return $this->hasMany(Reserva::class, 'id_empresa', 'id_empresa');
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'id_empresa', 'id_empresa');
    }

    public function clientes(): HasMany
    {
        return $this->hasMany(Cliente::class, 'id_empresa', 'id_empresa');
    }

    // ─────────────────────────────────────
    // SCOPES
    // ─────────────────────────────────────

    public function scopeActivas($query)
    {
        return $query->where('estado', 'Activo');
    }

    public function scopePorPlan($query, $plan)
    {
        return $query->where('plan', $plan);
    }

    // ─────────────────────────────────────
    // MÉTODOS HELPERS
    // ─────────────────────────────────────

    public function estaActiva(): bool
    {
        return $this->estado === 'Activo' 
            && now()->isBefore($this->fecha_fin_suscripcion);
    }

    public function necesitaRenovacion(): bool
    {
        return now()->diffInDays($this->fecha_fin_suscripcion) <= 30;
    }
}
```

### 2. Modificación de Modelos Existentes

#### 2.1 User Model - Agregar empresa_id

**Archivo**: `app/Models/User.php`

```php
// Agregar:
protected $fillable = [
    // ... existing
    'id_empresa',
];

public function empresa()
{
    return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
}

// En HasFactory:
public static function factory(): Factory
{
    return UserFactory::new();
}

public function withEmpresa(Empresa $empresa): static
{
    return $this->state(['id_empresa' => $empresa->id_empresa]);
}
```

#### 2.2 Ruta Model - Agregar empresa_id + Global Scope

**Archivo**: `app/Models/Ruta.php`

```php
<?php

namespace App\Models;

use App\Scopes\EmpresaScope;
use Illuminate\Database\Eloquent\Model;

class Ruta extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'rutas';
    protected $primaryKey = 'id_ruta';

    protected $fillable = [
        'id_empresa',  // ← NUEVO
        'nombre_ruta',
        'tipo',
        // ... resto
    ];

    // ─────────────────────────────────────
    // GLOBAL SCOPE
    // ─────────────────────────────────────

    protected static function booted()
    {
        static::addGlobalScope(new EmpresaScope);
    }

    // ─────────────────────────────────────
    // RELACIONES
    // ─────────────────────────────────────

    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
    }
}
```

#### 2.3 Otros Modelos - Agregar empresa_id

```php
// app/Models/Reserva.php
protected $fillable = ['id_empresa', 'id_fecha', ...];

public function empresa()
{
    return $this->belongsTo(Empresa::class, 'id_empresa', 'id_empresa');
}

protected static function booted()
{
    static::addGlobalScope(new EmpresaScope);
}

// app/Models/Cliente.php
// app/Models/Pago.php
// app/Models/FechaDisponible.php
// app/Models/Guia.php
// app/Models/Movilidad.php
// ... TODAS IGUAL
```

### 3. Global Scope - Filtrado Automático por Empresa

**Crear archivo**: `app/Scopes/EmpresaScope.php`

```php
<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EmpresaScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder instance.
     */
    public function apply(Builder $builder, Model $model): void
    {
        // ✅ Si usuario está autenticado, filtrar por su empresa
        if (auth()->check() && auth()->user()->id_empresa) {
            $builder->where(
                $model->getTable() . '.id_empresa',
                auth()->user()->id_empresa
            );
        }

        // ✅ Si es SuperAdmin sin empresa, mostrar TODO (opcional)
        // if (auth()->check() && auth()->user()->hasRole('superadmin')) {
        //     return;  // Sin filtro
        // }
    }
}
```

**Efecto**: Todas las queries se filtran automáticamente

```php
// Ejemplo:
$rutas = Ruta::all();  
// SQL Generated:
// SELECT * FROM rutas WHERE id_empresa = 1

$reservas = Reserva::where('estado', 'pendiente')->get();
// SQL Generated:
// SELECT * FROM reservas 
// WHERE estado = 'pendiente' AND id_empresa = 1
```

### 4. Middleware - Validar Acceso a Empresa

**Crear archivo**: `app/Http/Middleware/ValidateEmpresaAccess.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ValidateEmpresaAccess
{
    public function handle(Request $request, Closure $next)
    {
        $empresaSlug = $request->route('empresa_slug');

        if ($empresaSlug && auth()->check()) {
            $empresa = Empresa::where('slug_empresa', $empresaSlug)->first();

            if (!$empresa || $empresa->id_empresa !== auth()->user()->id_empresa) {
                abort(403, 'No tienes acceso a esta empresa');
            }

            // Guardar en contexto
            app()->instance('empresa', $empresa);
        }

        return $next($request);
    }
}
```

**Uso en rutas**:
```php
Route::middleware(['auth', 'validate.empresa.access'])
    ->prefix('/{empresa_slug}')
    ->group(function () {
        Route::resource('rutas', RutaController::class);
        Route::resource('reservas', ReservaController::class);
        // etc
    });
```

### 5. Rutas Multi-Tenant

**Archivo**: `routes/web.php`

```php
// ─────────────────────────────────────
// PÚBLICAS (sin autenticación)
// ─────────────────────────────────────

Route::get('/{empresa_slug}', [HomeController::class, 'index'])->name('home.tenant');
Route::get('/{empresa_slug}/rutas', [HomeController::class, 'listRutas']);
Route::get('/{empresa_slug}/rutas/{id}/descripcion', [HomeController::class, 'showRuta']);
Route::get('/{empresa_slug}/reserva/{ruta}', [ReservaClienteController::class, 'formulario']);

// ─────────────────────────────────────
// PROTEGIDAS (con autenticación)
// ─────────────────────────────────────

Route::middleware(['auth:sanctum', 'verified'])
    ->prefix('/{empresa_slug}')
    ->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::resource('rutas', RutaController::class);
        Route::resource('reservas', ReservaController::class);
        // ... etc
    });

// ─────────────────────────────────────
// SUPER ADMIN (multi-empresa)
// ─────────────────────────────────────

Route::middleware(['auth', 'can:superadmin'])
    ->prefix('/superadmin')
    ->group(function () {
        Route::resource('empresas', EmpresaController::class);
        Route::get('/reportes', [SuperAdminController::class, 'reportes']);
    });
```

---

## 🗄️ ESTRATEGIA DE MIGRACIÓN A MYSQL

### 1. Nuevas Migraciones

#### 1.1 Crear tabla Empresas

**Archivo**: `database/migrations/YYYY_MM_DD_create_empresas_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id('id_empresa');
            
            // Información Básica
            $table->string('nombre_empresa', 255)->unique();
            $table->string('slug_empresa', 255)->unique()->index();
            $table->text('descripcion')->nullable();
            $table->string('email_contacto', 255);
            $table->string('telefono', 20)->nullable();
            
            // Ubicación
            $table->string('pais', 100);
            $table->string('ciudad', 100);
            
            // Estado y Plan
            $table->enum('estado', ['Activo', 'Inactivo', 'Suspendido'])->default('Activo');
            $table->enum('plan', ['free', 'profesional', 'premium'])->default('free');
            $table->integer('limite_usuarios')->default(5);
            $table->date('fecha_inicio_suscripcion');
            $table->date('fecha_fin_suscripcion');
            
            // Branding
            $table->string('logo_url', 255)->nullable();
            $table->string('logo_animation_url', 255)->nullable();
            $table->string('color_primario', 7)->default('#dc030c');  // hex color
            $table->string('color_secundario', 7)->default('#FFFFFF');
            $table->string('font_family', 100)->default('Arial');
            
            // MercadoPago
            $table->text('mercadopago_access_token')->nullable();  // ENCRIPTADO
            $table->text('mercadopago_webhook_secret')->nullable(); // ENCRIPTADO
            
            // Redes Sociales
            $table->string('facebook_url', 255)->nullable();
            $table->string('instagram_url', 255)->nullable();
            $table->string('linkedin_url', 255)->nullable();
            $table->string('whatsapp_numero', 20)->nullable();
            
            // Configuración
            $table->decimal('comision_plataforma', 5, 2)->default(15.00);  // 15%
            $table->string('moneda', 3)->default('PEN');
            $table->time('horario_atencion_inicio')->default('08:00');
            $table->time('horario_atencion_fin')->default('18:00');
            $table->integer('dias_confirmacion')->default(3);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();  // Para recuperar empresas deletadas
            
            // Índices
            $table->index('estado');
            $table->index('plan');
            $table->index('fecha_fin_suscripcion');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
```

#### 1.2 Modificar Tablas Existentes - Agregar id_empresa

**Archivo**: `database/migrations/YYYY_MM_DD_add_empresa_to_existing_tables.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tablas que reciben id_empresa
        $tables = [
            'rutas',
            'clientes',
            'reservas',
            'pagos',
            'guias',
            'movilidads',
            'users',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'id_empresa')) {
                Schema::table($table, function (Blueprint $table_blueprint) use ($table) {
                    // Agregar columna id_empresa con FK
                    $table_blueprint->unsignedBigInteger('id_empresa')
                        ->after('id')
                        ->default(1);  // Default a empresa 1 (migración inicial)

                    // Si es tabla de rutas, no poner default después
                    if ($table === 'rutas') {
                        $table_blueprint->foreign('id_empresa')
                            ->references('id_empresa')
                            ->on('empresas')
                            ->onDelete('cascade');
                    }

                    $table_blueprint->index('id_empresa');
                });
            }
        }
    }

    public function down(): void
    {
        // Remover columnas
        $tables = ['rutas', 'clientes', 'reservas', 'pagos', 'guias', 'movilidads', 'users'];
        
        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'id_empresa')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->dropForeign(['id_empresa']);
                    $blueprint->dropIndex(['id_empresa']);
                    $blueprint->dropColumn('id_empresa');
                });
            }
        }
    }
};
```

#### 1.3 Corregir Composite Keys

**Problema Identificado** (database.md):
- `reserva_clientes`: Composite PK `[id_reserva, id_cliente]` - Sin validación de enum
- `movilidad_guias`: Composite PK `[id_movilidad, id_guia]` - Mismo
- `reserva_movilidads`: Composite PK - Mismo

**Solución**:

```php
// database/migrations/YYYY_MM_DD_fix_composite_keys.php

public function up(): void
{
    // reserva_clientes - CREAR DE NUEVO CON DEFINICIÓN EXPLÍCITA
    if (Schema::hasTable('reserva_clientes')) {
        Schema::table('reserva_clientes', function (Blueprint $table) {
            // Verificar que no tenga PK definido
            // Si está mal definido, recrear
        });
    }

    // Mejor: Crear tabla nuevamente
    Schema::create('reserva_clientes_new', function (Blueprint $table) {
        $table->foreignId('id_reserva')
            ->constrained('reservas', 'id_reserva')
            ->onDelete('cascade');
        $table->foreignId('id_cliente')
            ->constrained('clientes', 'id_cliente')
            ->onDelete('cascade');
        $table->timestamp('created_at')->useCurrent();
        $table->timestamp('updated_at')->useCurrent();
        
        // Composite Primary Key
        $table->primary(['id_reserva', 'id_cliente']);
    });

    // Migrar datos
    DB::statement('INSERT INTO reserva_clientes_new SELECT * FROM reserva_clientes');
    
    // Reemplazar
    Schema::drop('reserva_clientes');
    Schema::rename('reserva_clientes_new', 'reserva_clientes');
}
```

### 2. Script de Limpieza de Código Muerto

**Crear archivo**: `scripts/cleanup-dead-code.php`

```php
<?php

/**
 * Script para limpiar código comentado y funciones muertas
 * Uso: php scripts/cleanup-dead-code.php
 */

$paths = [
    'app/Http/Controllers',
    'app/Models',
];

$patterns = [
    '/\/\/\s*.*/',                              // Comentarios //
    '/\/\*[\s\S]*?\*\//',                       // Comentarios /* */
    '/public function .*?\{[\s\S]*?\}/m',       // Funciones vacías
    '/\$.*? = .*;/',                            // Variables muertas
];

foreach ($paths as $path) {
    $files = glob(base_path($path) . '/*.php');

    foreach ($files as $file) {
        $content = file_get_contents($file);
        $original = $content;

        // 1. Remover comentarios largos
        $content = preg_replace('/\/\*[\s\S]{1,3}\*\//', '', $content);

        // 2. Remover funciones comentadas
        $content = preg_replace('/\s*\/\/.*?function.*?\{[\s\S]*?\}/m', '', $content);

        // 3. Remover variables comentadas
        $content = preg_replace('/\s*\/\/\s*\$.*?;/m', '', $content);

        // Solo escribir si cambió
        if ($content !== $original) {
            file_put_contents($file, $content);
            echo "✅ Limpiado: {$file}\n";
        }
    }
}

echo "\n✨ Limpieza completada\n";
?>
```

**Uso**:
```bash
php scripts/cleanup-dead-code.php
```

### 3. Migración de BD - Estrategia Sin Pérdida de Datos

**Archivo**: `database/migrations/YYYY_MM_DD_migrate_to_mysql.php`

```php
<?php

return new class extends Migration
{
    /**
     * IMPORTANTE: Ejecutar en orden:
     * 1. php artisan migrate --step (crear nuevas columnas)
     * 2. php artisan migrate:refresh --seed (recrear BD si es dev)
     * 3. Verificar integridad con tests
     */
    
    public function up(): void
    {
        // Ya ejecutado en migraciones anteriores
        echo "✅ Cambios a MySQL completados\n";
        echo "   - Empresa multitenant añadido\n";
        echo "   - id_empresa en todas las tablas\n";
        echo "   - Global Scopes configurados\n";
        echo "   - Composite keys corregidas\n";
    }

    public function down(): void
    {
        //
    }
};
```

---

## 🖥️ INFRAESTRUCTURA DE ADMINISTRACIÓN

### 1. SuperAdmin Panel - Gestión de Empresas

#### 1.1 Nuevo Controller: EmpresaController

**Crear archivo**: `app/Http/Controllers/Admin/EmpresaController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EmpresaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:superadmin');  // Solo SuperAdmin
    }

    public function index()
    {
        $empresas = Empresa::all();
        
        return view('admin.empresas.index', compact('empresas'));
    }

    public function create()
    {
        return view('admin.empresas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre_empresa' => 'required|unique:empresas',
            'email_contacto' => 'required|email',
            'plan' => 'required|in:free,profesional,premium',
            'fecha_fin_suscripcion' => 'required|date|after:today',
        ]);

        $validated['slug_empresa'] = Str::slug($validated['nombre_empresa']);
        $validated['fecha_inicio_suscripcion'] = now()->date();

        Empresa::create($validated);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa creada exitosamente');
    }

    public function edit(Empresa $empresa)
    {
        return view('admin.empresas.edit', compact('empresa'));
    }

    public function update(Request $request, Empresa $empresa)
    {
        $validated = $request->validate([
            'nombre_empresa' => 'required|unique:empresas,nombre_empresa,' . $empresa->id_empresa . ',id_empresa',
            'plan' => 'required|in:free,profesional,premium',
            'estado' => 'required|in:Activo,Inactivo,Suspendido',
            'mercadopago_access_token' => 'nullable',
            'mercadopago_webhook_secret' => 'nullable',
        ]);

        $empresa->update($validated);

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa actualizada');
    }

    public function destroy(Empresa $empresa)
    {
        $empresa->delete();

        return redirect()->route('admin.empresas.index')
            ->with('success', 'Empresa eliminada');
    }
}
```

#### 1.2 Vistas AdminLTE

**Crear archivo**: `resources/views/admin/empresas/index.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Gestión de Empresas')

@section('plantilla')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-danger">
                    <h3 class="card-title">Empresas (SuperAdmin)</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.empresas.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus"></i> Nueva Empresa
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>Email</th>
                                <th>Plan</th>
                                <th>Estado</th>
                                <th>Vencimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($empresas as $empresa)
                            <tr>
                                <td>
                                    @if($empresa->logo_url)
                                        <img src="{{ $empresa->logo_url }}" height="30" alt="Logo">
                                    @endif
                                    {{ $empresa->nombre_empresa }}
                                </td>
                                <td>{{ $empresa->email_contacto }}</td>
                                <td>
                                    <span class="badge badge-{{ $empresa->plan === 'premium' ? 'danger' : 'warning' }}">
                                        {{ ucfirst($empresa->plan) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-{{ $empresa->estado === 'Activo' ? 'success' : 'danger' }}">
                                        {{ $empresa->estado }}
                                    </span>
                                </td>
                                <td>{{ $empresa->fecha_fin_suscripcion->format('Y-m-d') }}</td>
                                <td>
                                    <a href="{{ route('admin.empresas.edit', $empresa) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.empresas.destroy', $empresa) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
```

### 2. Panel de Ajustes de Marca (White-Label)

**Crear archivo**: `resources/views/admin/ajustes/brand.blade.php`

```blade
@extends('layouts.app')

@section('title', 'Ajustes de Marca')

@section('plantilla')

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger">
                    <h3 class="card-title">Personalización de Marca</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('empresa.brand.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Logo Section -->
                        <div class="form-group">
                            <label>Logo Principal</label>
                            <input type="file" name="logo_url" class="form-control" accept="image/*">
                            @if($empresa->logo_url)
                                <img src="{{ $empresa->logo_url }}" width="100" class="mt-2">
                            @endif
                        </div>

                        <!-- Colors Section -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Color Primario</label>
                                    <input type="color" name="color_primario" value="{{ $empresa->color_primario }}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Color Secundario</label>
                                    <input type="color" name="color_secundario" value="{{ $empresa->color_secundario }}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <!-- Social Media -->
                        <div class="form-group">
                            <label>Facebook</label>
                            <input type="url" name="facebook_url" value="{{ $empresa->facebook_url }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Instagram</label>
                            <input type="url" name="instagram_url" value="{{ $empresa->instagram_url }}" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>WhatsApp</label>
                            <input type="text" name="whatsapp_numero" value="{{ $empresa->whatsapp_numero }}" class="form-control" placeholder="+51987654321">
                        </div>

                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Preview Section -->
        <div class="col-md-4">
            <div class="card" style="background-color: {{ $empresa->color_primario }}; color: white;">
                <div class="card-header">
                    <h5 class="card-title">Vista Previa</h5>
                </div>
                <div class="card-body text-center">
                    @if($empresa->logo_url)
                        <img src="{{ $empresa->logo_url }}" width="100" alt="Logo" class="mb-3">
                    @endif
                    <h3>{{ $empresa->nombre_empresa }}</h3>
                    <p>{{ $empresa->descripcion }}</p>
                    <div class="mt-3">
                        @if($empresa->facebook_url)
                            <a href="{{ $empresa->facebook_url }}" class="btn btn-sm btn-light"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if($empresa->instagram_url)
                            <a href="{{ $empresa->instagram_url }}" class="btn btn-sm btn-light"><i class="fab fa-instagram"></i></a>
                        @endif
                        @if($empresa->whatsapp_numero)
                            <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $empresa->whatsapp_numero) }}" class="btn btn-sm btn-light"><i class="fab fa-whatsapp"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
```

---

## 🔐 SEGURIDAD Y PROCESAMIENTO DE PAGOS

### 1. Refactorizar MercadoPago - Crear Service Layer

**Crear archivo**: `app/Services/MercadoPagoService.php`

```php
<?php

namespace App\Services;

use App\Models\Empresa;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;

class MercadoPagoService
{
    private Empresa $empresa;

    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
        
        // Configurar con credenciales de la empresa
        MercadoPagoConfig::setAccessToken($empresa->mercadopago_access_token);
    }

    /**
     * Crear preferencia de pago
     */
    public function createPreference(array $data): object
    {
        $preferenceData = [
            "items" => $data['items'],
            "payer" => $data['payer'],
            "back_urls" => [
                "success" => route('mercadopago.success', ['empresa_slug' => $this->empresa->slug_empresa]),
                "failure" => route('mercadopago.failure', ['empresa_slug' => $this->empresa->slug_empresa]),
            ],
            "auto_return" => "approved",
            "external_reference" => $data['external_reference'] ?? uniqid(),
            "notification_url" => route('webhook.mercadopago', ['empresa_slug' => $this->empresa->slug_empresa]),
        ];

        try {
            $client = new PreferenceClient();
            return $client->create($preferenceData);
        } catch (\Exception $e) {
            throw new \RuntimeException("Error al crear preferencia: " . $e->getMessage());
        }
    }

    /**
     * Obtener estado del pago
     */
    public function getPayment(string $paymentId): object
    {
        try {
            $client = new PaymentClient();
            return $client->get($paymentId);
        } catch (\Exception $e) {
            throw new \RuntimeException("Error al obtener pago: " . $e->getMessage());
        }
    }

    /**
     * Validar firma del webhook ✅ CRÍTICO
     */
    public function validateWebhookSignature(
        string $xSignature,
        string $xRequestId,
        string $paymentId
    ): bool
    {
        $secret = $this->empresa->mercadopago_webhook_secret;
        
        if (!$secret) {
            throw new \RuntimeException("Webhook secret no configurado para empresa");
        }

        // Reconstruir firma
        $data = "{$xRequestId}:{$paymentId}";
        $parts = explode(',', $xSignature);

        foreach ($parts as $part) {
            if (strpos($part, '=') !== false) {
                list($alg, $sig) = explode('=', $part);
                
                if ($alg === 'sha256') {
                    $computedSig = hash_hmac('sha256', $data, $secret);
                    
                    if (hash_equals($computedSig, $sig)) {
                        return true;  // ✅ Válido
                    }
                }
            }
        }

        return false;  // ❌ Inválido
    }
}
```

### 2. Refactorizar Controller - Usar Service

**Archivo**: `app/Http/Controllers/MercadoPagoController.php` (MODIFICADO)

```php
<?php

namespace App\Http\Controllers;

use App\Services\MercadoPagoService;
use App\Models\Empresa;
use App\Models\Reserva;
use App\Models\Cliente;
use App\Models\Pago;
use Illuminate\Http\Request;

class MercadoPagoController extends Controller
{
    public function checkout(Request $request, string $empresa_slug)
    {
        $empresa = Empresa::where('slug_empresa', $empresa_slug)->firstOrFail();
        $service = new MercadoPagoService($empresa);

        // Validar datos (FormRequest después)
        $validated = $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'cantidad_personas' => 'required|integer|between:1,50',
            // ... resto de validaciones
        ]);

        try {
            $preference = $service->createPreference([
                'items' => [[
                    'title' => $request->input('nombre_ruta'),
                    'quantity' => 1,
                    'unit_price' => ($request->input('cantidad_personas') * $request->input('precio_actual')) * 0.5,
                ]],
                'payer' => [
                    'name' => $request->input('nombre'),
                    'surname' => $request->input('apellido'),
                    'email' => $request->input('email'),
                ],
                'external_reference' => 'reserva_' . uniqid(),
            ]);

            // Guardar en sesión
            session(['datos_reserva' => $validated]);

            return redirect()->away($preference->init_point);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function success(Request $request, string $empresa_slug)
    {
        $empresa = Empresa::where('slug_empresa', $empresa_slug)->firstOrFail();
        $service = new MercadoPagoService($empresa);

        // ✅ VALIDAR FIRMA
        $xSignature = $request->header('x-signature');
        $xRequestId = $request->header('x-request-id');
        $paymentId = $request->input('payment_id');

        if (!$service->validateWebhookSignature($xSignature, $xRequestId, $paymentId)) {
            \Log::error('Invalid webhook signature', ['payment_id' => $paymentId]);
            abort(403, 'Invalid signature');
        }

        try {
            $payment = $service->getPayment($paymentId);

            if ($payment->status === 'approved') {
                // Crear reserva...
                // (mismo código de antes, pero con $empresa->id_empresa)
            }
        } catch (\Exception $e) {
            return view('mercadopago.fallo2', ['error' => $e->getMessage()]);
        }
    }

    /**
     * WEBHOOK - MercadoPago llama aquí
     */
    public function webhook(Request $request, string $empresa_slug)
    {
        $empresa = Empresa::where('slug_empresa', $empresa_slug)->firstOrFail();
        $service = new MercadoPagoService($empresa);

        // ✅ VALIDAR FIRMA
        if (!$service->validateWebhookSignature(
            $request->header('x-signature'),
            $request->header('x-request-id'),
            $request->input('id')
        )) {
            return response()->json(['error' => 'Invalid signature'], 403);
        }

        // Procesar pago
        $paymentId = $request->input('id');
        $payment = $service->getPayment($paymentId);

        if ($payment->status === 'approved') {
            // Actualizar reserva, enviar confirmación, etc
        }

        return response()->json(['success' => true]);
    }
}
```

### 3. Webhook Configuración en MercadoPago

**Pasos**:
1. Login a MercadoPago Dashboard
2. Ir a Integración → Webhooks
3. Registrar URL:
   ```
   https://tudominio.com/{empresa_slug}/webhook/mercadopago
   ```
4. Eventos: `payment.created`, `payment.updated`

**Beneficio**:
- ✅ MercadoPago envía firma en cada webhook
- ✅ No hay confirmación manual de pago
- ✅ Totalmente seguro
- ✅ Escalable a múltiples empresas

---

## 📅 HOJA DE RUTA (TIMELINE)

### FASE 1: Refactorización de Base de Datos (4 Semanas)

**Objetivo**: Preparar BD para multi-tenancy sin pérdida de datos

#### Semana 1: Análisis y Diseño
- [ ] Revisar todas las migraciones existentes
- [ ] Documentar tipos de datos incompatibles
- [ ] Diseñar modelo de Empresa
- [ ] Crear script de validación de integridad

**Entregables**:
- Documento: "Mapeo de Migraciones"
- Script: "validate-database-integrity.php"

#### Semana 2: Implementar Cambios de BD
- [ ] Crear migración de tabla `empresas`
- [ ] Crear migración de agregar `id_empresa` a todas las tablas
- [ ] Corregir composite keys en pivot tables
- [ ] Crear índices para performance

**Entregables**:
- 3 nuevas migraciones
- Rollback seguro para cada una

#### Semana 3: Limpieza de Código
- [ ] Crear script `cleanup-dead-code.php`
- [ ] Remover funciones comentadas
- [ ] Remover variables muertas
- [ ] Ejecutar en controllers

**Entregables**:
- Script de limpieza
- Reporte de líneas eliminadas

#### Semana 4: Testing e Integración
- [ ] Tests de integridad de BD
- [ ] Tests de migraciones reversibles
- [ ] Ejecutar suite de tests (PHPUnit)
- [ ] Documentar issues encontrados

**Entregables**:
- Tests: `DatabaseIntegrityTest.php`
- Documento: "Resultados de Testing Fase 1"

---

### FASE 2: Implementación Multi-Tenancy (4 Semanas)

**Objetivo**: Hacer el sistema multi-tenant con Global Scopes

#### Semana 5: Modelos y Scopes
- [ ] Crear modelo `Empresa.php`
- [ ] Crear `EmpresaScope.php`
- [ ] Modificar todos los modelos (agregar empresa_id FK)
- [ ] Implementar relaciones en Empresa model

**Entregables**:
- Modelo Empresa con factory
- Global Scope funcionando
- 8+ modelos actualizados

#### Semana 6: Rutas y Middleware
- [ ] Crear middleware `ValidateEmpresaAccess`
- [ ] Refactorizar routes/web.php
- [ ] Agregar prefijo `/{empresa_slug}` a rutas protegidas
- [ ] Documentar estructura de URL

**Entregables**:
- Rutas multi-tenant funcionales
- Ejemplos de URL antigua vs nueva
- Tests de routing

#### Semana 7: Seeders y Fixtures
- [ ] Crear `EmpresaSeeder.php`
- [ ] Crear `EmpresaFactory.php`
- [ ] Generar datos de prueba
- [ ] Documentar cómo crear nueva empresa

**Entregables**:
- Seeders que crean 3 empresas de ejemplo
- Factory completa
- Documentación: "Cómo crear nueva empresa"

#### Semana 8: Testing Multi-Tenancy
- [ ] Tests de Global Scope
- [ ] Tests de acceso por empresa
- [ ] Tests de aislamiento de datos
- [ ] Performance tests con múltiples empresas

**Entregables**:
- 20+ tests de multi-tenancy
- Coverage report
- Documento: "Resultados Fase 2"

---

### FASE 3: Seguridad y Pagos (4 Semanas)

**Objetivo**: Implementar seguridad crítica y MercadoPago por empresa

#### Semana 9: Service Layer MercadoPago
- [ ] Crear `MercadoPagoService.php`
- [ ] Implementar validación de firma
- [ ] Agregar método `validateWebhookSignature()`
- [ ] Documentar endpoints webhook

**Entregables**:
- Service completo con 5+ métodos
- Documentación técnica de webhook
- Ejemplos de payloads

#### Semana 10: Refactorizar Controllers
- [ ] Actualizar `MercadoPagoController`
- [ ] Usar `MercadoPagoService` en lugar de lógica inline
- [ ] Agregar validación de firma en webhook
- [ ] Tests de controller con mock

**Entregables**:
- Controller refactorizado
- 15+ tests de pago
- Documentación de cambios

#### Semana 11: Implementar Webhooks
- [ ] Crear endpoint `/webhook/mercadopago` por empresa
- [ ] Documentar configuración en Dashboard MercadoPago
- [ ] Agregar rate limiting
- [ ] Implementar reintentos

**Entregables**:
- Webhook URL funcional
- Rate limiting: 10 webhooks/minuto
- Guía: "Configurar Webhooks en MP"

#### Semana 12: Testing de Seguridad
- [ ] Tests de validación de firma
- [ ] Tests de inyección SQL
- [ ] Tests de CSRF
- [ ] Tests de escalada de privilegios

**Entregables**:
- Security test suite (20+ tests)
- Documento: "Resultados Fase 3 - Security Audit"
- Reporte de vulnerabilidades cerradas

---

### FASE 4: Interfaz de Usuario y White-Label (4 Semanas)

**Objetivo**: Crear UI para superadmin y brand customization

#### Semana 13: SuperAdmin Panel
- [ ] Crear `EmpresaController` (CRUD)
- [ ] Crear vistas: `index.blade.php`, `create.blade.php`, `edit.blade.php`
- [ ] Implementar soft deletes para empresas
- [ ] Agregar búsqueda y filtros

**Entregables**:
- Controlador SuperAdmin
- 4 vistas AdminLTE
- 10+ tests de CRUD

#### Semana 14: Brand Customization Panel
- [ ] Crear vista `ajustes/brand.blade.php`
- [ ] Implementar upload de logo
- [ ] Color picker para primario/secundario
- [ ] Social media URLs

**Entregables**:
- Panel de branding funcional
- Upload de imágenes (S3/Local)
- Tests de validación de archivos

#### Semana 15: Frontend Dinámico
- [ ] Actualizar `layouts/app.blade.php` para leer from Empresa
- [ ] Remover hardcoding de "Outdoor Expeditions"
- [ ] Agregar logo/colores dinámicos
- [ ] Implementar cache de valores

**Entregables**:
- Layouts 100% dinámicos
- 27 elementos de branding adaptados
- Performance: < 50ms de cache

#### Semana 16: Documentación y Deploy
- [ ] Guía de deploymenty producción
- [ ] Documentación de API para partners
- [ ] Guía de onboarding para nuevas empresas
- [ ] SLA y términos de servicio

**Entregables**:
- `docs/deployment-guide.md`
- `docs/api-documentation.md`
- `docs/onboarding-guide.md`
- `docs/sla.md`

---

## 📊 PRESUPUESTO Y RECURSOS

### Equipo Recomendado

| Rol | Cantidad | Tiempo | Costo Estimado |
|---|---|---|---|
| **Backend Developer** | 1 | 16 semanas | $8,000-12,000 |
| **Frontend Developer** | 1 | 8 semanas | $4,000-6,000 |
| **QA Engineer** | 1 | 12 semanas | $3,000-4,500 |
| **DevOps/Infra** | 1 (0.5 FTE) | 4 semanas | $1,500-2,000 |
| **Product Manager** | 1 (0.5 FTE) | 16 semanas | $2,000-3,000 |
| **Total** | - | - | **$18,500-27,500** |

### Infraestructura

| Item | Costo Mensual | Notas |
|---|---|---|
| **Servidor MySQL Dedicado** | $150 | AWS RDS, 100GB SSD |
| **Redis Cache** | $30 | ElastiCache |
| **S3 Storage (Logos)** | $10 | 10GB máximo |
| **CDN (CloudFront)** | $20 | Imágenes caché global |
| **Monitoreo (New Relic)** | $100 | Performance & APM |
| **Backups Automatizados** | $50 | 30 días de retención |
| **Total Mensual** | **$360** | ~$4,300/año |

---

## ⚠️ RIESGOS Y MITIGACIÓN

### Riesgos Identificados

| Risk | Severidad | Probabilidad | Mitigación |
|---|---|---|---|
| **Pérdida de datos en migración** | 🔴 CRÍTICA | Media | Backups previos, dry-run, rollback plan |
| **Performance degradado con 10+ empresas** | 🔴 CRÍTICA | Media | Índices en id_empresa, caching, load testing |
| **Incompatibilidad de Eloquent con composite keys** | 🟡 ALTA | Alta | Pruebas exhaustivas, phpstan análisis |
| **Costo de infraestructura inesperado** | 🟠 MEDIA | Media | Presupuestar 30% margen, usar reserved instances |
| **Retraso en implementación de webhooks** | 🟠 MEDIA | Baja | Usar Postman para testing, documentar early |
| **Problemas de seguridad post-deploy** | 🔴 CRÍTICA | Baja | Security testing, code review, penetration testing |

### Plan de Contingencia

**Si BD se corrompe**:
```bash
# 1. Restaurar backup anterior
php artisan backup:restore
# 2. Ejecutar tests
php artisan test
# 3. Notificar stakeholders
```

**Si queries se vuelven lentas**:
```bash
# 1. Analizar queries
php artisan tinker
# > DB::enableQueryLog()
# 2. Agregar índices
php artisan migrate --create=add_missing_indexes_table
# 3. Implementar caching
```

---

## 📋 CHECKLIST DE IMPLEMENTACIÓN

### Pre-Implementación
- [ ] Backup completo de BD actual
- [ ] Backup de codebase actual
- [ ] Revisar todos los análisis previos (database.md, security.md, etc.)
- [ ] Reunión de kick-off con equipo
- [ ] Ambiente staging preparado

### Fase 1 - BD
- [ ] Crear modelo Empresa
- [ ] Crear migraciones
- [ ] Ejecutar dry-run
- [ ] Ejecutar migraciones reales
- [ ] Validar integridad
- [ ] Tests passing: ✅ 100%

### Fase 2 - Multi-Tenancy
- [ ] Global Scope funcionando
- [ ] Rutas multi-tenant
- [ ] Middleware de acceso
- [ ] Tests de aislamiento
- [ ] Tests passing: ✅ 100%

### Fase 3 - Seguridad
- [ ] Service MercadoPago creado
- [ ] Webhooks implementados
- [ ] Validación de firma
- [ ] Security tests
- [ ] Tests passing: ✅ 100%

### Fase 4 - UI
- [ ] SuperAdmin panel CRUD
- [ ] Brand customization panel
- [ ] Frontend dinámico
- [ ] Documentación
- [ ] UAT aprobado

### Post-Deploy
- [ ] Monitoreo 24/7 habilitado
- [ ] Alertas configuradas
- [ ] Backup diario verificado
- [ ] Analytics habilitado
- [ ] SLA documentado

---

## 🎉 ÉXITO - MÉTRICAS FINALES

### Antes vs Después

| Métrica | Antes | Después | Mejora |
|---|---|---|---|
| **Empresas Soportadas** | 1 (hardcoded) | N (unlimited) | ∞ |
| **Código Duplicado** | 0% necesario | 0% | 100% reutilizable |
| **Configuración Manual** | 27 elementos | 1 panel | 97% automatizado |
| **Tiempo Onboarding** | Manual | 1 día | -80% |
| **Vulnerabilidades Críticas** | 3 | 0 | ✅ 100% resueltas |
| **Cobertura de Tests** | 5% | 70%+ | +1300% |
| **Performance BD** | SQLite | MySQL | 10x+ velocidad |
| **Escalabilidad** | No | Horizontal | ✅ Cloud-ready |

---

## 📚 DOCUMENTACIÓN GENERADA

```
docs/
├── white-label-plan.md          (este documento)
├── security.md                  ✅ Generado
├── testing.md                   ✅ Generado
├── database.md                  ✅ Generado
├── backend.md                   ✅ Generado
├── frontend.md                  ✅ Generado
├── api.md                        ✅ Generado
├── routes.md                     ✅ Generado
├── system-overview.md            ✅ Generado
├── deployment-guide.md           (TO CREATE)
├── api-documentation.md          (TO CREATE)
├── onboarding-guide.md           (TO CREATE)
└── sla.md                        (TO CREATE)
```

---

## 🚀 PRÓXIMOS PASOS

1. **Aprobación Ejecutiva**: Revisar presupuesto y timeline
2. **Selección de Equipo**: Asignar recursos para Fase 1
3. **Ambiente Staging**: Preparar clon de BD para testing
4. **Kick-off Meeting**: 2026-05-15 (Semana de inicio)
5. **Sprint Planning**: Detallar tareas por semana

---

## 📞 CONTACTO Y SOPORTE

- **Product Manager**: [TBD]
- **Tech Lead**: [TBD]
- **DevOps**: [TBD]
- **Slack Channel**: #white-label-saas
- **Project Management**: Jira (board: AGENTS)

---

**Versión**: 1.0  
**Última Actualización**: 2026-05-08  
**Estado**: ✅ LISTO PARA IMPLEMENTACIÓN  
**Horizonte**: 16 Semanas (4 meses)  
**Inversión Total**: $18,500 - $27,500 + $4,300/año infraestructura

**Aprobado por**: [Signature pending]  
**Fecha de Aprobación**: [TBD]

---

## ANEXOS

### A. Diagrama de Arquitectura Multi-Tenant

```
┌─────────────────────────────────────┐
│         Internet Público            │
└──────────────┬──────────────────────┘
               │
        ┌──────▼──────┐
        │  Load       │
        │  Balancer   │
        └──────┬──────┘
               │
    ┌──────────┼──────────┐
    │          │          │
┌───▼──┐  ┌────▼───┐  ┌──▼────┐
│ App  │  │  App   │  │ App   │
│ Pod1 │  │  Pod2  │  │ Pod3  │
└──┬───┘  └────┬───┘  └──┬────┘
   │           │         │
   └─────┬─────┴─────┬───┘
         │           │
    ┌────▼────┐  ┌──▼─────┐
    │ MySQL   │  │ Redis   │
    │ (Multi) │  │ Cache   │
    └─────────┘  └─────────┘
         │
    ┌────▼─────┐
    │ S3       │
    │ Backups  │
    └──────────┘
```

### B. Modelo ER Simplificado

```
Empresa (1) ─────── (N) Ruta
         └────────── (N) Cliente
         └────────── (N) Reserva
         └────────── (N) Pago
         └────────── (N) User

Ruta (1) ────────── (N) FechaDisponible
      └─────────── (N) Reserva
      
Reserva (N) ──── (N) Cliente (via ReservaCliente)
        (N) ──── (N) Movilidad (via ReservaMovilidad)
        (1) ──── (N) Pago
```

### C. Checklist de Seguridad Post-Deploy

- [ ] MercadoPago webhooks validando firma
- [ ] Global Scope funcionando (sin data leaks)
- [ ] Contraseñas encriptadas (mercadopago_access_token)
- [ ] SSL/TLS en todas las conexiones
- [ ] Rate limiting en endpoints
- [ ] Logs sanitizados (sin datos sensibles)
- [ ] Backups diarios verificados
- [ ] Penetration testing completado
- [ ] OWASP Top 10 mitigaciones verificadas

---

**Documento de Estrategia: WHITE-LABEL SAAS MULTI-TENANT**  
**Confidencialidad**: Interno - Equipo Técnico + Ejecutivos  
**Distribución**: GitHub, Confluence, Drive compartido
