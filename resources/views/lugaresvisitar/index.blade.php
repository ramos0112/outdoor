@extends('layouts.admin-base')

@section('title', 'Lugares Disponibles')

@section('content_header')
    <h1>Lugares Disponibles de una Ruta</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            @can('lugares.crear')
                {{-- Importante: Mantener data-bs-target="#create" como tenías originalmente --}}
                <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#create">
                    <i class="fas fa-plus"></i> Agregar
                </button>
            @endcan
        </div>

        <div class="card-body">
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
                        {{-- Vacío para que DataTables cargue los datos --}}
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Modales: Asegúrate de que los archivos dentro de la carpeta coincidan --}}
        @include('lugaresvisitar.create')
        @include('lugaresvisitar.show')
        @include('lugaresvisitar.edit')
        @include('lugaresvisitar.delete')
    </div>
@stop

@section('css')
    {{-- Mantengo tus librerías originales --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet" />
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicialización de DataTable con sintaxis limpia
            var table = $('#tablaLugares').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('lugares.index') }}",
                    type: 'GET'
                },
                columns: [{
                        data: 0
                    }, // ID
                    {
                        data: 1
                    }, // Ruta
                    {
                        data: 2
                    }, // Lugar
                    {
                        data: 3,
                        orderable: false,
                        searchable: false
                    } // Botones
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                responsive: true,
                order: [
                    [0, 'desc']
                ]
            });

            // Lógica para que el modal de AGREGAR funcione (evita conflictos de versión)
            $('[data-bs-target="#create"]').on('click', function() {
                $('#create').modal('show');
            });

            // Lógica para llenar Modales de Ver y Editar
            // Lógica para el Modal de VER
            $('#modalShow').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var lugar = button.data('cliente'); // Extrae el objeto JSON enviado desde el Service
                var modal = $(this);

                modal.find('#show_id_lugar').text(lugar.id_lugar);
                modal.find('#show_nombre_ruta').text(lugar.ruta ? lugar.ruta.nombre_ruta : 'N/A');
                modal.find('#show_nombre_lugar').text(lugar.nombre_lugar);
            });

            // Lógica para el Modal de EDITAR
            $('#modalEdit').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var lugar = button.data('cliente');
                var modal = $(this);

                // Actualiza la URL del formulario con el ID correcto
                var url = "{{ route('lugares.update', ':id') }}".replace(':id', lugar.id_lugar);
                modal.find('#formEdit').attr('action', url);

                // Llena los campos
                modal.find('#edit_nombre_lugar').val(lugar.nombre_lugar);
                modal.find('#edit_id_ruta').val(lugar.id_ruta).trigger('change');
            });

            // Lógica para el Modal de ELIMINAR
            $('#modalDelete').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget);
                var lugar = button.data('cliente'); // Extraemos el objeto JSON
                var modal = $(this);

                // Construimos la URL dinámica para el destroy
                var url = "{{ route('lugares.destroy', ':id') }}".replace(':id', lugar.id_lugar);
                
                // Asignamos la URL al action del formulario
                modal.find('#formDelete').attr('action', url);
                
                // Mostramos información visual al usuario
                modal.find('#delete_nombre_lugar').text(lugar.nombre_lugar);
                modal.find('#delete_nombre_ruta').text(lugar.ruta ? lugar.ruta.nombre_ruta : 'N/A');
            });
        });
    </script>
@stop
@stop

