<!-- resources/views/paguinas/descripcionruta.blade.php -->
@extends('layouts.app')

@section('title', 'descripcionruta de rutas')

@section('plantilla')
    <section class="hero">
        
        <h1 class="text-3xl font-bold sm:text-2xl md:text-4xl">rutas <span
                class="text-danger">{{ $ruta->nombre_ruta }}</span></h1>
        <p>{{ $ruta->descripcion_general }}</p>
    </section>
    <section class="bg-dark py-3">
        <div class="container d-flex justify-content">
            <ul class="nav">
                <li class="nav-item">
                    <span class="nav-link text-white">📄 Descripción</span>
                </li>
                <li class="nav-item">
                    <span class="nav-link text-white">📍 Lugares</span>
                </li>
                <li class="nav-item">
                    <span class="nav-link text-white">🛎️ Servicios</span>
                </li>
                <li class="nav-item">
                    <span class="nav-link text-white">🏞️ rutass</span>
                </li>
            </ul>
        </div>
    </section>
    <section class="container my-5 text-white">
        <div class="row">
            <!-- Descripción -->
            <div class="col-md-6 mb-4">
                <div class="bg-dark p-4 rounded shadow-sm">
                    <h2 class="h5 mb-3">DESCRIPCIÓN:</h2>
                    @foreach ($ruta->detalles as $detalle)
                        <p>{{ $detalle->descripcion }}</p>
                    @endforeach
                    <h5 class="mt-4">Lugares a visitar:</h5>
                    <ul>
                        @foreach ($ruta->lugaresVisitar as $lugar)
                            <li>{{ $lugar->nombre_lugar }}</li>
                        @endforeach
                    </ul>
                    <h5 class="mt-4">Inclusiones:</h5>
                    <ul>
                        @foreach ($ruta->serviciosIncluidos as $servicio)
                            <li>{{ $servicio->servicio }}</li>
                        @endforeach
                    </ul>
                    <h5 class="mt-4">No incluye:</h5>
                    <ul>
                        <li>Comidas extras</li>
                        <li>Propinas</li>
                        <li>Servicios no especificados</li>
                    </ul>
                    <h5 class="mt-4">Restricciones:</h5>
                    <p>Niños mayores de 4 a 10 años pagan tarifa completa para este Full Day.</p>
                </div>
            </div>
            <!-- Imágenes y reserva -->
            <div class="col-md-6">
                <div class="row g-2 mb-4">
                    @foreach ($ruta->imagenes->shuffle()->take(4) as $img)
                        <div class="col-6">
                            <div class="rounded overflow-hidden" style="height: 200px;">
                                <img src="{{ $img->url_imagen }}" class="w-100 h-100 object-fit-cover"
                                    alt="Imagen de la ruta">
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="bg-dark p-4 rounded shadow-sm">
                    <div class="bg-light text-center p-3 rounded-3 mb-5">
                        <span class="fs-3 fw-bold text-success">Desde: </span>
                        <span class="text-danger text-decoration-line-through">S/.{{ $ruta->precio_regular }}</span>
                        <span class="fs-3 fw-bold text-success">S/.{{ $ruta->precio_actual }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <h4 class="fw-bold">Fechas Disponibles:</h4>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($ruta->fechasDisponibles as $fecha)
                                <div class="btn btn-outline-light">
                                    {{ \Carbon\Carbon::parse($fecha->fecha)->format('d/m/Y') }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <span class="fw-bold fs-2">Iinversion: <span class="text-success">S/. {{ $ruta->precio_actual }}</span></span>

                        <a href="{{ route('reserva.formulario', ['ruta' => $ruta->id_ruta]) }}" class="btn btn-success fw-bold btn-lg">
                            CONTINUAR
                        </a>
                        
                        
                                              
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-dark py-5 text-white text-center">
        <div class="container">
            <h2 class="display-5 fw-bold mb-2">También te puede interesar</h2>
            <p class="lead fst-italic mb-4">Un buen viajero no tiene planes fijos ni tampoco la intención de llegar</p>
        </div>
    </section>

    @include('paguinas.paqueterutas') <!-- Aquí ya estará disponible $rutas como colección -->
@endsection

