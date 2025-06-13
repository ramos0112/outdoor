<!-- resources/views/fechasdisponible/index.blade.php -->
@extends('adminlte::page')
@section('title', 'Fechas Disponibles')
@section('content_header')
    <h1>Fechas Disponibles de una ruta</h1>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <!-- Button trigger modal -->
                @can('fechas.crear')
                <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#create"><i class="fas fa-plus"></i> Agregar</button>
                @endcan
            </div>

            <!-- Tabla de Fechas Disponibles -->
            <div class="table-responsive mt-3">
                <table id="tablaFechas" class="table table-bordered table-striped w-100">
                    <thead class="table-dark text-center">
                        <tr >
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">Ruta</th>
                            <th class="text-center" scope="col">Fecha</th>
                            <th class="text-center" scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($fechas as $fecha)
                            <tr class="table-light">
                                <td>{{ $fecha->id_fecha }}</td>
                                <td>{{ $fecha->ruta->nombre_ruta }}</td>
                                <td>{{ $fecha->fecha }}</td>
                                <td>
                                    <!-- Ver -->
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#show{{ $fecha->id_fecha }}" title="Ver">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                    @can('fechas.editar')
                                    <!-- Editar -->
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#edit{{ $fecha->id_fecha }}" title="Editar">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                            @include('fechasdisponible.show')
                            @include('fechasdisponible.edit')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modal Crear --}}
        @include('fechasdisponible.create')
    </div>
@stop

@section('css')
    <!-- Estilos -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

@stop

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            // Inicializar DataTable con idioma en español
            $('#tablaFechas').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'

                },
                paging: true,  // Activar paginación
                ordering: true, // Permitir ordenar columnas
                searching: true, // Activar búsqueda
                lengthMenu: [10, 25, 50, 100], // Número de registros por página
                autoWidth: true, // Ajusta el ancho de las columnas automáticamente
                responsive: true, // Hace la tabla responsive para dispositivos móviles
                order: [[0, 'desc']]
            });

            // Inicializar Select2 en el campo de rutas dentro del modal de creación
            $('#create').on('shown.bs.modal', function () {
                $('#id_ruta').select2({ dropdownParent: $('#create') });
            });

            // Inicializar y destruir Select2 dinámicamente en los modales de edición
            $('.modal').on('shown.bs.modal hidden.bs.modal', function (event) {
                var modalId = $(this).attr('id');
                var selectId = '#id_ruta_edit' + modalId.replace('edit', ''); // Construir el ID del select

                if (event.type === 'shown') {
                    if (!$(selectId).hasClass('select2-hidden-accessible')) {
                        $(selectId).select2({ dropdownParent: $(this) });
                    }
                } else {
                    $(selectId).select2('destroy');
                }
            });
        });
    </script>
@stop
