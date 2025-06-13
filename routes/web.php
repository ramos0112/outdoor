<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DetalleRutaController;
use App\Http\Controllers\FechaDisponibleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReservaClienteController;
use App\Http\Controllers\LugarVisitarController;
use App\Http\Controllers\ServicioIncluidoController;
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\RutaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\GuiaController;
use App\Http\Controllers\MovilidadController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ListarReservasController;
use App\Http\Controllers\ReservaMovilidadController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

// ** Rutas públicas (accesibles sin autenticación) **

// Página principal
Route::get('/', [HomeController::class, 'home'])->name('home');

// Blog
Route::get('/blog', [HomeController::class, 'blog'])->name('blog.web');

// Mostrar rutas por tipo (ej: trekking, aventura)
Route::get('/rutas/tipo/{tipo}', [HomeController::class, 'rutasPorTipo'])->name('rutas.tipo');

// Mostrar descripción de una ruta específica
Route::get('/rutas/{id_ruta}/descripcion', [HomeController::class, 'mostrarDescripcion'])
    ->where('id_ruta', '[0-9]+')
    ->name('rutas.descripcion');

// Formulario de reserva
Route::get('/reserva/{ruta}', [ReservaClienteController::class, 'formulario'])->name('reserva.formulario');

// Procesar reserva
Route::post('/reserva', [ReservaClienteController::class, 'store'])->name('reservas.store');

// ** MercadoPago **
Route::post('/checkout', [MercadoPagoController::class, 'checkout'])->name('mercadopago.checkout');
Route::get('/mercadopago/success', [MercadoPagoController::class, 'success'])->name('mercadopago.success');
Route::get('/mercadopago/failure', [MercadoPagoController::class, 'failure'])->name('mercadopago.failure');

// ** Rutas protegidas por autenticación **

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // Dashboard y perfil de usuario
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/Profile', [UsuarioController::class, 'Perfil']);

    // ** Gestión de reservas **
    Route::resource('gestionreservas', ReservaController::class);
    Route::post('/gestionreservas/buscar', [ReservaController::class, 'buscarPorDNI'])->name('gestionreservas.buscar');
    Route::resource('listareservas', ListarReservasController::class);
    Route::get('/buscar-cliente/{numero_documento}', [ListarReservasController::class, 'buscarPorDocumento']);
    Route::get('/api/fechas-por-ruta/{id}', [ListarReservasController::class, 'obtenerFechasPorRuta']);

    // ** Gestión de rutas, lugares, servicios, y movilidad **
    Route::resource('rutas', RutaController::class);
    Route::resource('detalleruta', DetalleRutaController::class);
    Route::resource('fechas', FechaDisponibleController::class);
    Route::resource('lugares', LugarVisitarController::class);
    Route::resource('servicios', ServicioIncluidoController::class);
    Route::resource('imageness', ImagenController::class);
    Route::resource('movilidades', MovilidadController::class);
    Route::resource('reservasmovilidad', ReservaMovilidadController::class);

    // ** Gestión de clientes, guías y pagos **
    Route::resource('clientes', ClienteController::class);
    Route::resource('guias', GuiaController::class);
    Route::resource('pagos', PagoController::class);

    // ** Gestión de roles y permisos **
    Route::resource('roles', RoleController::class);
    Route::resource('permisos', PermissionController::class);
    Route::post('/permisos/update', [PermissionController::class, 'update'])->name('permisos.update');
});


