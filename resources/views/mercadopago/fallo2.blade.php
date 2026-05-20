@extends('layouts.app')

@section('title', 'Pago Fallido')

<<section class="hero hero-nosotros text-center">
    <h1>¡Algo salió mal!</h1>
    <p class="text-white">Tu pago se procesoró correctamente.</p>
    <p class="text-white">pero tu reserva no se pudo registrar.</p>
    <p class="text-white">Por favor, contacta a soporte.</p>

    <a href="{{ route('home') }}" class="btn btn-outline-danger mt-4">Volver al inicio</a>
</section>
