@extends('adminlte::page')

@section('title', 'Servicios Incluidos')

@section('content_header')
    <h1>Servicios Incluidos de una Ruta</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @can('servicios.crear') {{-- can permite mostrar el boton --}}
            <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#create">
               <i class="fas fa-plus"></i> Agregar
            </button>
            @endcan
        </div>

        <div class="card-body">
            {{-- Tabla --}}
            <div class="table-responsive">
                <table id="tablaServicios" class="table table-bordered table-striped w-100 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Ruta</th>
                            <th>Servicio</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($servicios as $servicio)
                            <tr>
                                <td>{{ $servicio->id_servicio }}</td>
                                <td>{{ $servicio->ruta->nombre_ruta }}</td>
                                <td>{{ $servicio->servicio }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#show{{ $servicio->id_servicio }}"> <i class="fas fa-eye"></i>
                                    </button>
                                    @can('servicios.editar')
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $servicio->id_servicio }}"> <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                            @include('serviciosincluidos.edit')
                            @include('serviciosincluidos.show')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include('serviciosincluidos.create')
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#tablaServicios').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                lengthMenu: [10, 25, 50, 100],
                autoWidth: true,
                responsive: true,
                order: [[0, 'desc']]
            });

            // Select2 para modal crear
            $('#create').on('shown.bs.modal', function () {
                $('#id_ruta').select2({
                    dropdownParent: $('#create')
                });
            });

            // Select2 dinámico en modales editar
            $('.modal').on('shown.bs.modal hidden.bs.modal', function (event) {
                var modalId = $(this).attr('id');
                var selectId = '#id_ruta_edit' + modalId.replace('edit', '');

                if (event.type === 'shown') {
                    if (!$(selectId).hasClass('select2-hidden-accessible')) {
                        $(selectId).select2({
                            dropdownParent: $(this)
                        });
                    }
                } else {
                    $(selectId).select2('destroy');
                }
            });
        });
    </script>
@stop
