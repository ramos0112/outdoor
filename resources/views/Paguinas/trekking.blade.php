<!-- resources/views/paguinas/rutas.blade.php -->
@extends('layouts.app') <!-- Extiende el layout base -->

@section('title', 'Rutas') <!-- Título de la página -->

@section('plantilla')
    <section class="hero">
        <h1 class="text-3xl font-bold sm:text-2xl md:text-4xl">trekking <span class="text-danger">Expeditions</span></h1>
        <p>Sábados, domingos y feriados</p>
        <!-- Menú de íconos -->
        <div class="menu-section hidden lg:flex mb-5"> <!-- mb-4 para separar el menú del contenido siguiente -->
            <div class="menu-item"><i class="fas fa-home"></i><a href="/">Home</a></div>
            <div class="menu-item"><i class="fas fa-road"></i><a href="/ruta">Rutas</a></div>
            <div class="menu-item"><i class="fas fa-book"></i><a href="#">Blog</a></div>
            <div class="menu-item"><i class="fas fa-hiking"></i><a href="trekking">Trekking</a></div>
            <div class="menu-item"><i class="fas fa-envelope"></i><a href="#">Contacto</a></div>
        </div>
    </section>
    <section class="bg-dark py-3">
        <div class="container d-flex justify-content">

        </div>
    </section>
    @include('paguinas.paqueterutas')
@endsection
