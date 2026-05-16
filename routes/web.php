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
use App\Http\Controllers\MovilidadReporteController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ListarReservasController;
use App\Http\Controllers\ReservaMovilidadController;
use App\Http\Controllers\MercadoPagoController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConfiguracionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ActivityLogController;

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

// Renta de autos estática
Route::get('/renta-cars', function () {
    return view('rentacars.index');
})->name('rentacars.index');

Route::get('/renta-cars/{slug}', function ($slug) {
    return view('rentacars.show', compact('slug'));
})->where('slug', '[A-Za-z0-9\-]+')->name('rentacars.show');

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
/*     Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard'); */
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/Profile', [UsuarioController::class, 'Perfil']);

    // ** Administración de Configuración (White Label) **
    Route::get('/configuracion', [ConfiguracionController::class, 'edit'])->name('configuracion.edit');
    Route::put('/configuracion', [ConfiguracionController::class, 'update'])->name('configuracion.update');

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
    Route::resource('imagen', ImagenController::class);
    Route::resource('movilidades', MovilidadController::class);
    Route::resource('reservasmovilidad', ReservaMovilidadController::class);

    // ** Gestión de clientes, guías y pagos **
    Route::resource('clientes', ClienteController::class);
    Route::resource('guias', GuiaController::class);
    Route::resource('pagos', PagoController::class);

    // ** Gestión de roles y permisos **
    Route::resource('roles', RoleController::class);
    Route::resource('permisos', PermissionController::class);
    //Route::post('/permisos/update', [PermissionController::class, 'update'])->name('permisos.update');

    Route::get('/movilidad', [MovilidadReporteController::class, 'index'])->name('movilidad.reporte');
    Route::get('/movilidad-reporte/rutas', [MovilidadReporteController::class, 'rutasPorFecha']);
    Route::get('/movilidad-reporte/movilidades', [MovilidadReporteController::class, 'movilidadesPorRuta']);
    Route::get('/movilidad-reporte/manifiesto', [MovilidadReporteController::class, 'manifiestoPorMovilidad']);

    Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');

});


