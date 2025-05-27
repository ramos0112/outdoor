@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>GUIAS</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#createGuide">Agregar Guía</button>
        </div>
        <div class="card-body">
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

            <!-- Tabla de Guias -->
            <div class="table-responsive">
                <table id="tablaGuias" class="table table-bordered table-striped w-100">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Teléfono</th>
                            <th>Email</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($guias as $guia)
                            <tr>
                                <td class="text-center">{{ $guia->id_guia }}</td>
                                <td class="text-center">{{ $guia->nombre }}</td>
                                <td class="text-center">{{ $guia->apellido }}</td>
                                <td class="text-center">{{ $guia->telefono }}</td>
                                <td class="text-center">{{ $guia->email }}</td>
                                <td class="text-center">
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#show{{ $guia->id_guia }}">Ver</button>
                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $guia->id_guia }}">Editar</button>
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#delete{{ $guia->id_guia }}">Eliminar</button>
                                </td>
                            </tr>
                            @include('guias.show')
                            @include('guias.edit')
                            @include('guias.delete')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para crear nueva guía -->
    @include('guias.create')

@stop

@section('css')
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
            $('#tablaGuias').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true
            });
        });
    </script>
@stop
