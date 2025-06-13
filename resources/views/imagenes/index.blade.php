@extends('adminlte::page')

@section('title', 'Imágenes de Rutas')

@section('content_header')
    <h1>Imágenes de las Rutas</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @can('imagenes.crear')
                <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#create">
                    <i class="fas fa-plus"></i> Agregar
                </button>
            @endcan
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tablaImagenes" class="table table-bordered table-striped w-100 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Ruta</th>
                            <th>Imagen</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($imagenes as $imagen)
                            <tr>
                                <td>{{ $imagen->id_imagen }}</td>
                                <td>{{ $imagen->ruta->nombre_ruta }}</td>
                                <td>
                                    <img src="{{ asset($imagen->url_imagen) }}" alt="Imagen de Ruta" width="100" class="img-thumbnail">
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#show{{ $imagen->id_imagen }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    @can('imagenes.editar')
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $imagen->id_imagen }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endcan

                                    @can('imagenes.eliminar')
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#confirmDelete{{ $imagen->id_imagen }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>

                            @include('imagenes.edit')
                            @include('imagenes.show')
                            @include('imagenes.delete')
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @include('imagenes.create')
    </div>
@stop

@section('css')
    <!-- Estilos -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#tablaImagenes').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true,
                lengthMenu: [10, 25, 50, 100],
                autoWidth: true,
                order: [[0, 'desc']]
            });

            // Select2 en modal crear
            $('#create').on('shown.bs.modal', function () {
                $('#id_ruta').select2({
                    dropdownParent: $('#create')
                });
            });

            // Select2 en modales de edición dinámicos
            $('.modal').on('shown.bs.modal hidden.bs.modal', function (event) {
                var modal = $(this);
                var selectId = modal.find('select.select2');

                if (event.type === 'shown') {
                    if (!selectId.hasClass('select2-hidden-accessible')) {
                        selectId.select2({
                            dropdownParent: modal
                        });
                    }
                } else {
                    selectId.select2('destroy');
                }
            });
        });
    </script>
@stop
