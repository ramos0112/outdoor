<?php

use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DetalleRutaController;
use App\Http\Controllers\FechaDisponibleController;
use App\Http\Controllers\homeController;
use Illuminate\Support\Facades\Route;
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

Route::get('/', function () {
    return view('welcome');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/Profile', [UsuarioController::class, 'Perfil']);

    Route::get('/Profile2', [UsuarioController::class, 'Perfil2']);

    Route::resource('gestionreservas', ReservaController::class);
    Route::post('/gestionreservas/buscar', [ReservaController::class, 'buscarPorDNI'])->name('gestionreservas.buscar');

    Route::resource('listareservas', ListarReservasController::class);
    Route::get('/buscar-cliente/{numero_documento}', [ListarReservasController::class, 'buscarPorDocumento']);
    Route::get('/api/fechas-por-ruta/{id}', [ListarReservasController::class, 'obtenerFechasPorRuta']);


    Route::resource('rutas', RutaController::class);
    
    Route::resource('detalleruta', DetalleRutaController::class);

    Route::resource('fechas', FechaDisponibleController::class);

    Route::resource('lugares', LugarVisitarController::class);
    
    Route::resource('servicios', ServicioIncluidoController::class);

    Route::resource('imageness', ImagenController::class);

    Route::resource('clientes', ClienteController::class);

    Route::resource('movilidades', MovilidadController::class);

    Route::resource('reservasmovilidad', ReservaMovilidadController::class);

    Route::resource('guias', GuiaController::class);

    Route::resource('pagos', PagoController::class);

 
});

Route::post('/checkout', [MercadoPagoController::class, 'checkout'])->name('mercadopago.checkout');
Route::get('/mercadopago/success', [MercadoPagoController::class, 'success'])->name('mercadopago.success');
Route::get('/mercadopago/failure', [MercadoPagoController::class, 'failure'])->name('mercadopago.failure');

// Mostrar rutas por tipo (ej: trekking, aventura)
Route::get('/rutas/tipo/{tipo}', [HomeController::class, 'rutasPorTipo'])->name('rutas.tipo');

// Mostrar descripción de una ruta específica (solo si el ID es numérico)
Route::get('/rutas/{id_ruta}/descripcion', [HomeController::class, 'mostrarDescripcion'])
    ->where('id_ruta', '[0-9]+')
    ->name('rutas.descripcion');

// Blog
Route::get('/blog', [HomeController::class, 'blog'])->name('blog.web');

// Página de inicio
Route::get('/', [HomeController::class, 'home'])->name('home');



Route::get('/reserva/{ruta}', [ReservaClienteController::class, 'formulario'])->name('reserva.formulario');

Route::post('/reserva', [ReservaClienteController::class, 'store'])->name('reservas.store');
/*
use App\Models\Cliente;
use App\Models\Ruta;
use App\Models\FechaDisponible;
use App\Models\Reserva;
use App\Mail\ConfirmacionReserva;
use Illuminate\Support\Facades\Mail;

Route::get('/vista-correo', function () {
    $cliente = Cliente::find(1);
    $ruta = Ruta::find(1);
    $fechaDisponible = FechaDisponible::find(1);
    $reserva = Reserva::find(1);

    $mail = new ConfirmacionReserva($cliente, $ruta, $fechaDisponible, $reserva);

    // Renderiza el correo como HTML
    return $mail->render();
});
*/