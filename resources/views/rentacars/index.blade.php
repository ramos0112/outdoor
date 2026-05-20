@extends('layouts.app')

@section('title', 'Renta de Autos')

@section('meta_description', 'Alquila movilidades turísticas en La Libertad: vans, sprinters y buses para excursiones y eventos. Reserva fácil con Ayniforest.')
@section('canonical_url', url()->current())
@section('og_title', 'Alquiler de Movilidades - Ayniforest')
@section('og_description', 'Flota moderna para viajes, excursiones y eventos. Opciones de 6 a 50 pasajeros.')
@section('og_image', asset('imagenes/logo.webp'))

@section('head')
<link rel="stylesheet" href="{{ asset('css/rentacars.css?v=' . time()) }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
@endsection
 
@section('plantilla')
<section class="hero-rentacars" style="background-image: url('{{ asset('imagenes/rentacars/background-card.webp') }}');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1 class="hero-title">Alquiler de Movilidades Turísticas</h1>
    </div>
</section>

<section class="rentacars-hero py-5">
    <div class="container">
        <div class="rentacars-header text-center">
            <p class="subtitle">Movilidad para tus viajes y excursiones</p>
            <p class="description">En Ayni Forest contamos con una moderna flota de unidades diseñadas para brindar experiencias de viaje cómodas,
                 seguras y organizadas en cada recorrido. Ponemos a disposición movilidades ideales para traslados turísticos, viajes corporativos,
                  excursiones, delegaciones, eventos y servicios privados en La Libertad y el norte del Perú.
                <br>
                <br>
                    Explora nuestras opciones de alquiler de autos y descubre la libertad de viajar a tu ritmo con Ayni Forest. ¡Reserva tu unidad hoy mismo y prepárate para una experiencia de viaje excepcional!
                </p>
        </div>

        <div class="rentacars-swiper swiper mt-5">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <article class="rentacars-card">
                        <div class="card-media">
                            <img src="{{ asset('imagenes/rentacars/van-ejecutiva.webp') }}" alt="Van 6 pasajeros">
                        </div>
                        <div class="card-body">
                            <h2>Vehículos Ejecutivos </h2>
                            <p class="capacity">👤 6 pasajeros</p>
                        </div>
                        <a href="{{ route('rentacars.show', ['slug' => 'vehiculo-ejecutivo']) }}" class="card-button">Ver más</a>
                    </article>
                </div>

                <div class="swiper-slide">
                    <article class="rentacars-card">
                        <div class="card-media">
                            <img src="{{ asset('imagenes/rentacars/van-11.webp') }}" alt="Van 11 pasajeros">
                        </div>
                        <div class="card-body">
                            <h2>Van Turística </h2>
                            <p class="capacity">👤 11 pasajeros</p>
                            <p class="capacity">👤 15 pasajeros</p>
                        </div>
                        <a href="{{ route('rentacars.show', ['slug' => 'van-turistica']) }}" class="card-button">Ver más</a>
                    </article>
                </div>

                <div class="swiper-slide">
                    <article class="rentacars-card">
                        <div class="card-media">
                            <img src="{{ asset('imagenes/rentacars/Sprinter.webp') }}" alt="Sprinter 17 pasajeros">
                        </div>
                        <div class="card-body">
                            <h2>Sprinter Turística</h2>
                            <p class="capacity">👤 17 pasajeros</p>
                            <p class="capacity">👤 20 pasajeros</p>
                        </div>
                        <a href="{{ route('rentacars.show', ['slug' => 'Sprinter-confort']) }}" class="card-button">Ver más</a>
                    </article>
                </div>

                <div class="swiper-slide">
                    <article class="rentacars-card">
                        <div class="card-media">
                            <img src="{{ asset('imagenes/rentacars/buses.webp') }}" alt="Buses Turísticos">
                        </div>
                        <div class="card-body">
                            <h2>Buses Turísticos</h2>
                            <p class="capacity">👤 33 pasajeros</p>
                            <p class="capacity">👤 50 pasajeros</p>
                        </div>
                        <a href="{{ route('rentacars.show', ['slug' => 'buses-turisticos']) }}" class="card-button">Ver más</a>
                    </article>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const swiper = new Swiper('.rentacars-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30,
                },
            },
        });
    });
</script>
@endsection
