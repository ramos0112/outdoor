@extends('layouts.app')

@section('title', 'Detalle de unidad')

@section('meta_description', isset($slug) ? \Illuminate\Support\Str::title(str_replace('-', ' ', $slug)) . ' — alquiler de movilidades turísticas: características, capacidad y tarifas.' : 'Alquiler de movilidades turísticas. Información y reservas.')
@section('canonical_url', url()->current())
@section('og_title', isset($slug) ? \Illuminate\Support\Str::title(str_replace('-', ' ', $slug)) : 'Detalle de unidad')
@section('og_description', isset($slug) ? \Illuminate\Support\Str::title(str_replace('-', ' ', $slug)) . ' — alquiler de movilidades turísticas: características, capacidad y tarifas.' : 'Alquiler de movilidades turísticas. Información y reservas.')
@section('og_image', asset('imagenes/og-image.jpg'))

@section('head')
<link rel="stylesheet" href="{{ asset('css/rentacars.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endsection
 
@section('plantilla')

@php
    $backgroundHero = match ($slug) {

        'vehiculo-ejecutivo' => asset('imagenes/rentacars/van-ejecutiva-2.webp'),

        'van-turistica' => asset('imagenes/rentacars/van-turistica-2.webp'),

        'Sprinter-confort' => asset('imagenes/rentacars/solati-confort-2.webp'),

        'buses-turisticos' => asset('imagenes/rentacars/buses-turisticos-2.webp'),

        default => asset('imagenes/rentacars/default-2.webp'),
    };

@endphp
<section class="hero-rentacars" style="background-image: url('{{ $backgroundHero }}');">
    
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Alquiler de Movilidades Turísticas</h1>
    </div>
</section>

<section class="rentacars-detail py-5 mt-5">
    <div class="container">
        <div class="detail-header text-center mb-5">
            <span class="detail-badge">Renta de Movilidades Turisticas</span>
            @if($slug == 'vehiculo-ejecutivo')
                <h1>Vehículo Ejecutivo</h1>
            @elseif($slug == 'van-turistica')
                <h1>Van Turística</h1>
            @elseif($slug == 'Sprinter-confort')
                <h1>Sprinter Confort</h1>
            @elseif($slug == 'buses-turisticos')
                <h1>Buses Turísticos</h1>
            @else
                <h1>Unidad Desconocida</h1>
            @endif
            <p class="detail-intro">
                @if($slug == 'vehiculo-ejecutivo')
                    La mejor opción para quienes buscan privacidad, comodidad y una experiencia de viaje más exclusiva. 
                    Ideales para traslados ejecutivos, viajes familiares, recorridos personalizados y servicios privados 
                    dentro y fuera de la ciudad.
                    <br>
                    Nuestras unidades ejecutivas combinan confort, seguridad y practicidad, permitiendo disfrutar cada 
                    trayecto de manera tranquila y organizada.

                @elseif($slug == 'van-turistica')
                    Perfecta para grupos pequeños que desean disfrutar experiencias organizadas con mayor comodidad y cercanía. 
                    Ideal para city tours, full days, escapadas regionales y recorridos turísticos en grupo.
                    <br>
                    Con capacidad para 11 a 15 pasajeros, estas unidades ofrecen un ambiente acogedor y dinámico para explorar cada destino con libertad y buena energía.
                    Gracias a su diseño práctico y confortable, esta unidad brinda una experiencia dinámica y segura 
                    para disfrutar cada destino con tranquilidad.

                @elseif($slug == 'Sprinter-confort')
                    Diseñada para brindar una experiencia de viaje más cómoda, amplia y organizada. 
                    Ideal para grupos turísticos, delegaciones, eventos y recorridos corporativos en 
                    diferentes destinos del norte del Perú.
                    <br>
                    Su moderno equipamiento permite disfrutar trayectos más confortables y dinámicos, 
                    ofreciendo mayor comodidad durante rutas de corta y larga distancia.

                @elseif($slug == 'buses-turisticos')
                    Nuestras unidades de mayor capacidad están diseñadas para brindar comodidad, 
                    seguridad y una experiencia de viaje confiable en excursiones, promociones, 
                    delegaciones, eventos y recorridos grupales.
                    <br>
                    Gracias a sus espacios amplios y equipamiento moderno, nuestros buses permiten 
                    disfrutar viajes organizados y confortables, ideales para rutas turísticas de 
                    corta y larga distancia en La Libertad y el norte del Perú.

                @else
                    Esta unidad no existe todavía en el catálogo estático.
                @endif
            </p>
        </div>

        <div class="detail-layout">
            <div class="detail-gallery">
                @if($slug == 'vehiculo-ejecutivo')
                    <div class="gallery-mosaic">
                        <div class="gallery-main">
                            <a href="{{ asset('imagenes/rentacars/van-ejecutiva-1.webp') }}" data-lightbox="gallery-vehiculo-ejecutivo">
                                <img src="{{ asset('imagenes/rentacars/van-ejecutiva-1.webp') }}" alt="Vehículo Ejecutivo - Imagen 1">
                            </a>
                        </div>
                        <div class="gallery-side">
                            <a href="{{ asset('imagenes/rentacars/van-ejecutiva-2.webp') }}" data-lightbox="gallery-vehiculo-ejecutivo">
                                <img src="{{ asset('imagenes/rentacars/van-ejecutiva-2.webp') }}" alt="Vehículo Ejecutivo - Imagen 2">
                            </a>
                            <a href="{{ asset('imagenes/rentacars/van-ejecutiva-3.webp') }}" data-lightbox="gallery-vehiculo-ejecutivo">
                                <img src="{{ asset('imagenes/rentacars/van-ejecutiva-3.webp') }}" alt="Vehículo Ejecutivo - Imagen 3">
                            </a>
                        </div>
                    </div>
                @elseif($slug == 'van-turistica')
                    <div class="gallery-mosaic">
                        <div class="gallery-main">
                            <a href="{{ asset('imagenes/rentacars/van-turistica-1.webp') }}" data-lightbox="gallery-van-turistica">
                                <img src="{{ asset('imagenes/rentacars/van-turistica-1.webp') }}" alt="Van Turística - Imagen 1">
                            </a>
                        </div>
                        <div class="gallery-side">
                            <a href="{{ asset('imagenes/rentacars/van-turistica-2.webp') }}" data-lightbox="gallery-van-turistica">
                                <img src="{{ asset('imagenes/rentacars/van-turistica-2.webp') }}" alt="Van Turística - Imagen 2">
                            </a>
                            <a href="{{ asset('imagenes/rentacars/van-turistica-3.webp') }}" data-lightbox="gallery-van-turistica">
                                <img src="{{ asset('imagenes/rentacars/van-turistica-3.webp') }}" alt="Van Turística - Imagen 3">
                            </a>
                        </div>
                    </div>
                @elseif($slug == 'Sprinter-confort')
                    <div class="gallery-mosaic">
                        <div class="gallery-main">
                            <a href="{{ asset('imagenes/rentacars/solati-confort-1.webp') }}" data-lightbox="gallery-Sprinter-confort">
                                <img src="{{ asset('imagenes/rentacars/solati-confort-1.webp') }}" alt="Sprinter Confort - Imagen 1">
                            </a>
                        </div>
                        <div class="gallery-side">
                            <a href="{{ asset('imagenes/rentacars/solati-confort-2.webp') }}" data-lightbox="gallery-Sprinter-confort">
                                <img src="{{ asset('imagenes/rentacars/solati-confort-2.webp') }}" alt="Sprinter Confort - Imagen 2">
                            </a>
                            <a href="{{ asset('imagenes/rentacars/solati-confort-3.webp') }}" data-lightbox="gallery-Sprinter-confort">
                                <img src="{{ asset('imagenes/rentacars/solati-confort-3.webp') }}" alt="Sprinter Confort - Imagen 3">
                            </a>
                        </div>
                    </div>
                @elseif($slug == 'buses-turisticos')
                    <div class="gallery-mosaic">
                        <div class="gallery-main">
                            <a href="{{ asset('imagenes/rentacars/buses-turisticos-1.webp') }}" data-lightbox="gallery-buses-turisticos">
                                <img src="{{ asset('imagenes/rentacars/buses-turisticos-1.webp') }}" alt="Buses Turísticos - Imagen 1">
                            </a>
                        </div>
                        <div class="gallery-side">
                            <a href="{{ asset('imagenes/rentacars/buses-turisticos-2.webp') }}" data-lightbox="gallery-buses-turisticos">
                                <img src="{{ asset('imagenes/rentacars/buses-turisticos-2.webp') }}" alt="Buses Turísticos - Imagen 2">
                            </a>
                            <a href="{{ asset('imagenes/rentacars/buses-turisticos-3.webp') }}" data-lightbox="gallery-buses-turisticos">
                                <img src="{{ asset('imagenes/rentacars/buses-turisticos-3.webp') }}" alt="Buses Turísticos - Imagen 3">
                            </a>
                        </div>
                    </div>
                @else
                    <div class="gallery-mosaic">
                        <div class="gallery-main">
                            <a href="{{ asset('imagenes/rentacars/default-1.webp') }}" data-lightbox="gallery-default">
                                <img src="{{ asset('imagenes/rentacars/default-1.webp') }}" alt="Unidad Desconocida - Imagen 1">
                            </a>
                        </div>
                        <div class="gallery-side">
                            <a href="{{ asset('imagenes/rentacars/default-2.webp') }}" data-lightbox="gallery-default">
                                <img src="{{ asset('imagenes/rentacars/default-2.webp') }}" alt="Unidad Desconocida - Imagen 2">
                            </a>
                            <a href="{{ asset('imagenes/rentacars/default-3.webp') }}" data-lightbox="gallery-default">
                                <img src="{{ asset('imagenes/rentacars/default-3.webp') }}" alt="Unidad Desconocida - Imagen 3">
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <aside class="detail-panel">

                <!-- Servicios a bordo -->
                <div class="detail-section">
                    <h3 class="detail-section-title">Servicios a Bordo</h3>
                    <ul class="detail-services">
                        @if($slug == 'vehiculo-ejecutivo')
                            <li><i class="fas fa-wifi"></i> Aire acondicionado</li>
                            <li><i class="fas fa-music"></i> Asientos premium</li>
                            <li><i class="fas fa-tv"></i> Espacio confortable</li>
                            <li><i class="fas fa-coffee"></i> Movilidad equipada</li>
                        @elseif($slug == 'van-turistica')
                            <li><i class="fas fa-wifi"></i> Aire acondicionado</li>
                            <li><i class="fas fa-music"></i> Música ambiental</li>
                            <li><i class="fas fa-snowflake"></i> Climatización</li>
                            <li><i class="fas fa-plug"></i> Puertos USB</li>
                        @elseif($slug == 'Sprinter-confort')
                            <li><i class="fas fa-wifi"></i>Asientos reclinables</li>
                            <li><i class="fas fa-utensils"></i>Puerto USB</li>
                            <li><i class="fas fa-microphone"></i>Amplio espacio interior</li>
                            <li><i class="fas fa-first-aid"></i> Kit de primeros auxilios</li>
                        @elseif($slug == 'buses-turisticos')
                            <li><i class="fas fa-wifi"></i>Asientos reclinables</li>
                            <li><i class="fas fa-tv"></i>Puertos USB</li>
                            <li><i class="fas fa-restroom"></i>Portaequipajes</li>
                            <li><i class="fas fa-utensils"></i>Aire acondicionado</li>
                        @else
                            <li><i class="fas fa-check"></i> Servicios estándar</li>
                        @endif
                    </ul>
                </div>

                <!-- Incluye -->
                <div class="detail-section">
                    <h3 class="detail-section-title">Incluye</h3>
                    <ul class="detail-includes">
                        @if($slug == 'vehiculo-ejecutivo')
                            <li><i class="fas fa-check-circle"></i> Seguro de viaje</li>
                            <li><i class="fas fa-check-circle"></i> Conductor profesional</li>
                            <li><i class="fas fa-check-circle"></i> Combustible incluido</li>
                            <li><i class="fas fa-check-circle"></i> Asistencia 24/7</li>
                        @elseif($slug == 'van-turistica')
                            <li><i class="fas fa-check-circle"></i> Seguro de viaje</li>
                            <li><i class="fas fa-check-circle"></i> Chofer experimentado</li>
                            <li><i class="fas fa-check-circle"></i> Kilometraje ilimitado</li>
                            <li><i class="fas fa-check-circle"></i> GPS incluido</li>
                            <li><i class="fas fa-check-circle"></i> Impuestos incluidos</li>
                        @elseif($slug == 'Sprinter-confort')
                            <li><i class="fas fa-check-circle"></i> Seguro de ley</li>
                            <li><i class="fas fa-check-circle"></i> Conductores Profesionales</li>       
                            <li><i class="fas fa-check-circle"></i> GPS Satelital</li>
                            <li><i class="fas fa-check-circle"></i> Combustible incluido</li>
                            <li><i class="fas fa-check-circle"></i> Puertos USB</li>
                        @elseif($slug == 'buses-turisticos')
                            <li><i class="fas fa-check-circle"></i> Seguro turístico</li>
                            <li><i class="fas fa-check-circle"></i> GPS Satelital</li>
                            <li><i class="fas fa-check-circle"></i> Seguro vehícular</li>
                            <li><i class="fas fa-check-circle"></i> Impuestos de ley incluidos</li>
                        @else
                            <li><i class="fas fa-check-circle"></i> Servicios básicos incluidos</li>
                        @endif
                    </ul>
                </div>

                <button type="button" class="detail-action-button" id="openRentModal" data-vehicle="
                    @if($slug == 'vehiculo-ejecutivo') Vehículo Ejecutivo
                    @elseif($slug == 'van-turistica') Van Turística
                    @elseif($slug == 'Sprinter-confort') Sprinter Confort
                    @elseif($slug == 'buses-turisticos') Buses Turísticos
                    @else Unidad
                    @endif
                ">
                    Solicitar Disponibilidad
                </button>
            </aside>
        </div>
    </div>
</section>

<div class="rentacars-modal-backdrop hidden" id="rentacarsModalBackdrop">
    <div class="rentacars-modal" id="rentacarsModal">
        <div class="modal-header">
            <h2>Solicitar Disponibilidad</h2>
            <button type="button" class="modal-close" id="closeRentModal">×</button>
        </div>
        <p class="modal-description">Completa los datos y envía tu solicitud.</p>
        <form id="rentacarsForm">
            <label>
                Nombre Completo
                <input type="text" id="customerName" placeholder="Tu nombre completo" required>
            </label>
            <label>
                Fecha de inicio
                <input type="date" id="startDate" required>
            </label>
            <label>
                Fecha de fin
                <input type="date" id="endDate" required>
            </label>
            <label>
                Destino
                <input type="text" id="destination" placeholder="Ciudad o punto de destino" required>
            </label>
            <button type="submit" class="modal-submit-btn">Enviar Solicitud</button>
        </form>
    </div>
</div>
<div id="rentacarsWhatsappConfig" data-whatsapp="{{ preg_replace('/[^0-9]/', '', \App\Models\Configuracion::obtener()->whatsapp_numero ?? '933 329 650') }}" class="d-none"></div>
@endsection

@section('scripts')
<script src="{{ asset('js/rentacars.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>
@endsection
