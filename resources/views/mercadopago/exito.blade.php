@extends('layouts.app')

@section('title', 'Pago Exitoso')

@section('plantilla')
    <section class="container text-center py-5">
        <h1 class="text-success display-4">¡Gracias por tu reserva!</h1>
        <p class="lead">Tu pago ha sido procesado correctamente.</p>

        <div class="card shadow-sm mt-4 mx-auto" style="max-width: 500px;">
            <div class="card-body">
                <h5 class="card-title text-primary">Resumen del Pago</h5>
                <ul class="list-group list-group-flush text-start">
                    <li class="list-group-item"><strong>ID del pago:</strong> {{ request('payment_id') }}</li>
                    <li class="list-group-item"><strong>Estado:</strong> {{ request('collection_status') }}</li>
                    <li class="list-group-item"><strong>Referencia:</strong> {{ request('external_reference') }}</li>
                </ul>
            </div>
        </div>

        <a href="{{ route('home') }}" class="btn btn-outline-primary mt-4">Volver al inicio</a>
    </section>
@endsection
