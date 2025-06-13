@extends('adminlte::page')

@section('title', 'Lugares Disponibles')

@section('content_header')
    <h1>Lugares Disponibles de una Ruta</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @can('lugares.crear')
            <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#create">
               <i class="fas fa-plus"></i> Agregar
            </button>
            @endcan
        </div>

        <div class="card-body">
            {{-- Tabla --}}
            <div class="table-responsive">
                <table id="tablaLugares" class="table table-striped table-bordered table-hover w-100 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Ruta</th>
                            <th>Lugar</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lugares as $lugar)
                            <tr>
                                <td>{{ $lugar->id_lugar }}</td>
                                <td>{{ $lugar->ruta->nombre_ruta }}</td>
                                <td>{{ $lugar->nombre_lugar }}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#show{{ $lugar->id_lugar }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @can('lugares.editar')
                                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#edit{{ $lugar->id_lugar }}">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>
                                    @endcan
                                </td>
                            </tr>
                            @include('lugaresvisitar.show')
                            @include('lugaresvisitar.edit')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include('lugaresvisitar.create')
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
        $(document).ready(function() {
            $('#tablaLugares').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true,
                lengthMenu: [10, 25, 50, 100],
                order : [[0, 'desc']]
            });

            // Select2 en modales
            $('#create').on('shown.bs.modal', function() {
                $('#id_ruta').select2({
                    dropdownParent: $('#create')
                });
            });

            $('.modal').on('shown.bs.modal hidden.bs.modal', function(event) {
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
