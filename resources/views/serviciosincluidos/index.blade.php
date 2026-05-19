@extends('layouts.admin-base')

@section('title', 'Servicios Incluidos')

@section('content_header')
    <h1>Servicios Incluidos de una Ruta</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @can('servicios.crear')
                {{-- can permite mostrar el boton --}}
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

                    </tbody>
                </table>
            </div>
        </div>
        @include('serviciosincluidos.create')
        @include('serviciosincluidos.edit')
        @include('serviciosincluidos.delete')
        @include('serviciosincluidos.show')
        @include('serviciosincluidos.delete')
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
            var table = $('#tablaServicios').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('servicios.index') }}",
                columns: [{
                        data: 0
                    }, // ID
                    {
                        data: 1
                    }, // Ruta
                    {
                        data: 2
                    }, // Servicio
                    {
                        data: 3,
                        orderable: false,
                        searchable: false
                    } // Acciones
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                order: [
                    [0, 'desc']
                ]
            });

            // Select2 para modal crear
            $('#create').on('shown.bs.modal', function() {
                $('#id_ruta').select2({
                    dropdownParent: $('#create')
                });
            });

            // Select2 dinámico en modales editar
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

            // Lógica para llenar modal Ver
            $('#modalShow').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var data = button.data('objeto');
                $(this).find('#show_id').text(data.id_servicio);
                $(this).find('#show_ruta').text(data.ruta.nombre_ruta);
                $(this).find('#show_servicio').text(data.servicio);
            });

            // Lógica para llenar modal Editar
            $('#modalEdit').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var data = button.data('objeto');
                var url = "{{ route('servicios.update', ':id') }}".replace(':id', data.id_servicio);

                $(this).find('#formEdit').attr('action', url);
                $(this).find('#edit_servicio').val(data.servicio);
                $(this).find('#edit_id_ruta').val(data.id_ruta).trigger('change');
            });

            // Lógica para el Modal de ELIMINAR Servicios
            $('#modalDelete').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Botón que disparó el modal
                var servicio = button.data('objeto'); // Extrae el objeto JSON enviado desde el Service
                var modal = $(this);

                // 1. Construir la URL de eliminación dinámicamente usando el ID correcto
                var url = "{{ route('servicios.destroy', ':id') }}".replace(':id', servicio.id_servicio);
                modal.find('#formDelete').attr('action', url);

                // 2. Llenar la información visual para confirmación
                modal.find('#delete_servicio').text(servicio.servicio);
                modal.find('#delete_ruta').text(servicio.ruta ? servicio.ruta.nombre_ruta : 'N/A');
            });
        });
    </script>
@stop

