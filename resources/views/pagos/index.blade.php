@extends('layouts.admin-base')

@section('title', 'Pagos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Lista de Pagos</h1>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                {{-- Mensajes de éxito o error --}}
                @if ($message = Session::get('success'))
                    <div class="alert alert-success mt-2">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger mt-2">
                        <p>{{ $message }}</p>
                    </div>
                @endif
            </div>
            <div class="table-responsive mt-3">
                <table id="tablaPagos" class="table table-bordered table-striped w-100">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-center">ID</th>
                            <th class="text-center">Reserva ID</th>
                            <th class="text-center">Método de Pago</th>
                            <th class="text-center">Monto Pagado</th>
                            <th class="text-center">Fecha de Pago</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
            
            {{-- MODAL ÚNICO: Se incluye una sola vez fuera del bucle --}}
            @include('pagos.show')
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#tablaPagos').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('pagos.index') }}",
                    type: 'GET'
                },
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: false, // Mapeado por ID descendente en backend
                searching: true,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { targets: '_all', className: 'text-center' }
                ]
            });
        });
    </script>

    <script>
        // JS para cargar dinámicamente el modal 'show' único
        const modalShowPago = document.getElementById('modalShowPago');
        if(modalShowPago) {
            modalShowPago.addEventListener('show.bs.modal', event => {
                const button = event.relatedTarget;
                const pago = JSON.parse(button.getAttribute('data-pago'));
                
                // Asegúrate de cambiar los IDs dentro de tu archivo 'pagos.show' para que coincidan con estos:
                if(document.getElementById('verIdPago')) document.getElementById('verIdPago').innerText = pago.id_pago;
                if(document.getElementById('verIdReserva')) document.getElementById('verIdReserva').innerText = pago.id_reserva;
                if(document.getElementById('verMetodoPago')) document.getElementById('verMetodoPago').innerText = pago.metodo_pago;
                if(document.getElementById('verMontoPagado')) document.getElementById('verMontoPagado').innerText = 'S/. ' + parseFloat(pago.monto_pagado).toFixed(2);
                if(document.getElementById('verFechaPago')) document.getElementById('verFechaPago').innerText = pago.fecha_pago;
            });
        }
    </script>
@stop
