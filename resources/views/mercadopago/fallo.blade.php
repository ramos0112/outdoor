@extends('layouts.app')

@section('title', 'Pago Fallido')

@section('plantilla')
    <section class="container text-center py-5">
        <h1 class="text-danger display-4">¡Algo salió mal!</h1>
        <p class="lead">No pudimos procesar tu pago.</p>
        <p>Por favor, intenta nuevamente o contacta a soporte.</p>

        <a href="{{ route('home') }}" class="btn btn-outline-danger mt-4">Volver al inicio</a>
    </section>
@endsection
