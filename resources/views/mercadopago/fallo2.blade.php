@extends('layouts.app')

@section('title', 'Pago Fallido')


@section('plantilla')
    <section class="container text-center py-5">
        <h1 class="text-danger display-4">¡Algo salió mal!</h1>
        <p class="lead">Tu pago se procesoró correctamente.</p>
        <p></p>pero tu reserva no se pudo registrar</p>
        <p>Por favor, contacta a soporte.</p>

        <a href="{{ route('home') }}" class="btn btn-outline-danger mt-4">Volver al inicio</a>
    </section>
@endsection
