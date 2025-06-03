@extends('adminlte::page')

@section('title', 'Movilidades')

@section('content_header')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="mb-0">Lista de Movilidades</h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#create">Agregar Movilidad</button>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-body">
                <!-- Tabla -->
                <div class="table-responsive mt-3">
                    <table id="tablaGuias" class="table table-bordered table-striped w-100">
                        <thead class="table-dark text-center">
                            <tr>
                                <th>ID</th>
                                <th>Capacidad</th>
                                <th>Estado</th>
                                <th>Guías</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($movilidades as $movilidad)
                                <tr>
                                    <td class="text-center">{{ $movilidad->id_movilidad }}</td>
                                    <td class="text-center">{{ $movilidad->capacidad }}</td>
                                    <td class="text-center">
                                        @if ($movilidad->estado == 'Disponible')
                                            <span class="badge bg-success">Disponible</span>
                                        @elseif ($movilidad->estado == 'Ocupado')
                                            <span class="badge bg-danger">Ocupado</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($movilidad->guias->isEmpty())
                                            <span class="text-muted">Sin guías asignados</span>
                                        @else
                                            <ul class="mb-0 list-unstyled">
                                                @foreach ($movilidad->guias as $guia)
                                                    <li>{{ $guia->nombre }} {{ $guia->apellido }}</li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#show{{ $movilidad->id_movilidad }}" title="Ver">
                                            <i class="bi bi-eye-fill"></i>
                                        </button>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#edit{{ $movilidad->id_movilidad }}" title="Editar">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#delete{{ $movilidad->id_movilidad }}" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @include('movilidades.edit')
                                @include('movilidades.show')
                                @include('movilidades.delete')
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @include('movilidades.create')
            </div>
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // DataTable
            $('#tablaGuias').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true
            });

            // Select2 en modal de creación
            $('#create').on('shown.bs.modal', function() {
                $('#id_guia').select2({
                    dropdownParent: $('#create'),
                    multiple: true
                });
            });

            // Select2 en modales de edición
            $('.modal').on('shown.bs.modal hidden.bs.modal', function(event) {
                var modalId = $(this).attr('id');
                var selectId = '#id_guia_edit' + modalId.replace('edit', '');

                if (event.type === 'shown') {
                    if (!$(selectId).hasClass('select2-hidden-accessible')) {
                        $(selectId).select2({
                            dropdownParent: $(this),
                            multiple: true
                        });
                    }
                } else {
                    if ($(selectId).hasClass('select2-hidden-accessible')) {
                        $(selectId).select2('destroy');
                    }
                }
            });
        });
    </script>
@stop
