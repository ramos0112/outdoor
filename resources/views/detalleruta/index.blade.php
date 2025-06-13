@extends('adminlte::page')

@section('title', 'Detalle de Rutas')

@section('content_header')
    <h1>Detalle de Rutas</h1>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <!-- Botón agregar -->
                @can('detalleruta.crear')
                    <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#create"><i
                            class="fas fa-plus"></i>
                        Agregar
                    </button>
                @endcan
            </div>

            <!-- Tabla -->
            <div class="table-responsive mt-3">
                <table class="table" id="detallerutas">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Ruta</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detallerutas as $detalleruta)
                            <tr>
                                <td class="text-center">{{ $detalleruta->id_detalle }}</td>
                                <td>{{ $detalleruta->ruta->nombre_ruta ?? 'Ruta no encontrada' }}</td>
                                <td>{{ \Illuminate\Support\Str::words($detalleruta->descripcion, 5, '...') }}</td>
                                <td>
                                    <!-- Ver -->
                                    <button type="button" class="btn btn-primary btn-sm btn-ver"
                                        data-id="{{ $detalleruta->id_detalle }}"
                                        data-ruta="{{ $detalleruta->ruta->nombre_ruta }}"
                                        data-descripcion="{{ $detalleruta->descripcion }}" data-bs-toggle="modal"
                                        data-bs-target="#modalShow" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                    <!-- Editar -->
                                    @can('detalleruta.editar')
                                        <button type="button" class="btn btn-success btn-sm btn-editar"
                                            data-id="{{ $detalleruta->id_detalle }}" data-idruta="{{ $detalleruta->id_ruta }}"
                                            data-descripcion="{{ $detalleruta->descripcion }}" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                    @endcan
                                    <!-- Eliminar -->
                                    @can('detalleruta.eliminar')
                                        <button type="button" class="btn btn-danger btn-sm btn-eliminar"
                                            data-id="{{ $detalleruta->id_detalle }}"
                                            data-ruta="{{ $detalleruta->ruta->nombre_ruta }}"
                                            data-descripcion="{{ $detalleruta->descripcion }}" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar" title="Eliminar">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Modales -->
            @include('detalleruta.create')
            @include('detalleruta.show')
            @include('detalleruta.edit')
            @include('detalleruta.delete')
        </div>
    </div>
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            // DataTable
            var table = $('#detallerutas').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                processing: true,
                serverSide: false,
                paging: true,
                searching: true,
                ordering: true,
                lengthMenu: [10, 25, 50, 100],
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });

            // Ver detalle
            $('.btn-ver').click(function() {
                $('#show_id').text($(this).data('id'));
                $('#show_ruta').text($(this).data('ruta'));
                $('#show_descripcion').text($(this).data('descripcion'));
            });

            // Editar
            $('.btn-editar').click(function() {
                let id = $(this).data('id');
                $('#edit_id_ruta').val($(this).data('idruta')).trigger('change');
                $('#edit_descripcion').val($(this).data('descripcion'));
                $('#formEditDetalleRuta').attr('action', "{{ url('detalleruta') }}/" + id);
            });

            // Eliminar
            $('.btn-eliminar').click(function() {
                const id = $(this).data('id');
                $('#delete_ruta').text($(this).data('ruta'));
                $('#delete_descripcion').text($(this).data('descripcion'));
                $('#formDeleteDetalleRuta').attr('action', "{{ url('detalleruta') }}/" + id);
            });

            // Select2 en modales
            $('#edit_id_ruta').select2({
                dropdownParent: $('#modalEdit')
            });
            $('#id_ruta').select2({
                dropdownParent: $('#create')
            });
        });
    </script>
@stop
