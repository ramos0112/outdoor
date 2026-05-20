@extends('layouts.app')

@section('title', 'Pago Fallido')
<section class="hero hero-nosotros text-center">
    <h1 >¡Algo salió mal!</h1>
    <p class="text-white">No pudimos procesar tu pago.</p>
    <p class="text-white">Por favor, intenta nuevamente o contactanos </p>
    <p>
        <<i class="fas fa-phone text-white "></i>
            <a href="https://acortar.link/vcswna" target="_blank" class="location-link">+51-933 329 650</a>
    </p>

    <a href="{{ route('home') }}" class="btn btn-outline-danger mt-4">Volver al inicio</a>
</section>
