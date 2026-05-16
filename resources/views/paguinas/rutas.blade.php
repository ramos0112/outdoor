@extends('layouts.app')

@section('title', 'Rutas')

@section('plantilla')
    <link rel="stylesheet" href="{{ asset('css/paquetes.css') }}">
    @php
        $hero = match (strtolower($tipo)) {
            'diarios' => [
                'titulo' => 'Tours Diarios',
                'descripcion' => 'Explora nuevos destinos cada día desde Trujillo',
                'clase' => 'hero-diarios',
            ],

            'weekend' => [
                'titulo' => 'Explora la libertad',
                'descripcion' => 'Escápate el fin de semana y vive nuevas aventuras',
                'clase' => 'hero-weekend',
            ],

            default => [
                'titulo' => 'Tours & Aventuras',
                'descripcion' => 'Explora nuevos destinos desde Trujillo',
                'clase' => 'hero-default',
            ],
        };
    @endphp

    <section class="hero {{ $hero['clase'] }}">
        <h1 class="text-3xl font-bold sm:text-2xl md:text-4xl">
            {{ $hero['titulo'] }}
        </h1>

        <p class="text-white">
            {{ $hero['descripcion'] }}
        </p>
    </section>
    <!-- Espaciador (opcional si hay más contenido abajo) -->
    <section class="bg-dark py-3">
        <div class="container d-flex justify-content-center"></div>
    </section>
    @include('paguinas.paqueterutas', ['tipo' => $tipo])
@endsection
