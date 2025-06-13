@extends('adminlte::page')

@section('title', 'Pagos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Lista de Pagos</h1>
        <!--<button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#create">Agregar Pago</button>-->
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
                        @foreach ($pagos as $pago)
                            <tr>
                                <td class="text-center">{{ $pago->id_pago }}</td>
                                <td class="text-center">{{ $pago->id_reserva }}</td>
                                <td class="text-center">{{ $pago->metodo_pago }}</td>
                                <td class="text-center">{{ $pago->monto_pagado }}</td>
                                <td class="text-center">{{ $pago->fecha_pago }}</td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#show{{ $pago->id_pago }}" title="Ver">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    {{-- <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $pago->id_pago }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete{{ $pago->id_pago }}" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button> --}}
                                </td>
                            </tr>
                            {{-- @include('pagos.edit') --}}
                            @include('pagos.show')
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- @include('pagos.create') --}}
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
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
@stop
