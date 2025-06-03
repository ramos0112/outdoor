@extends('layouts.app')

@section('title', 'Rutas')

@section('plantilla')
    <section class="hero">
        <h1 class="text-3xl font-bold sm:text-2xl md:text-4xl">
            {{ ucfirst($tipo) }} <span class="text-danger">Expeditions</span>
        </h1>
        <p>Sábados, domingos y feriados</p>

    </section>
        <!-- Espaciador (opcional si hay más contenido abajo) -->
    <section class="bg-dark py-3">
        <div class="container d-flex justify-content-center"></div>
    </section>
    @include('paguinas.paqueterutas', ['tipo' => $tipo])
@endsection
