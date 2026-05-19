@extends('layouts.admin-base')

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
                    </tbody>
                </table>
            </div>
        </div>
        @include('imagen.show')
        @include('imagen.edit')
        @include('imagen.delete')
        @include('imagen.create')
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
        $(document).ready(function() {
            var table = $('#tablaImagenes').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('imagen.index') }}",
                columns: [{
                    data: 0
                }, {
                    data: 1
                }, {
                    data: 2
                }, {
                    data: 3,
                    orderable: false
                }],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            // Función para obtener URL correcta (Cloudinary vs Local)
            function getImgUrl(url) {
                return url.startsWith('http') ? url : "{{ asset('') }}" + url;
            }

            // Modal Ver
            $('#modalShow').on('show.bs.modal', function(event) {
                var data = $(event.relatedTarget).data('objeto');
                $(this).find('#show_ruta').text(data.ruta.nombre_ruta);
                $(this).find('#show_img').attr('src', getImgUrl(data.url_imagen));
            });

            // Modal Editar
            $('#modalEdit').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                var data = button.data('objeto'); // Datos que vienen del Service
                var modal = $(this);

                // Configura la URL del formulario
                var urlAction = "{{ route('imagen.update', ':id') }}".replace(':id', data.id_imagen);
                modal.find('#formEdit').attr('action', urlAction);

                // Llena los campos
                modal.find('#edit_id_ruta').val(data.id_ruta).trigger('change');
                modal.find('#edit_url_imagen').val(data.url_imagen);

                // Actualiza la miniatura actual
                var imgUrl = data.url_imagen.startsWith('http') ? data.url_imagen : "{{ asset('') }}" +
                    data.url_imagen;
                modal.find('#edit_img_actual').attr('src', imgUrl);
            });

            // Modal Eliminar
            $('#modalDelete').on('show.bs.modal', function(event) {
                var data = $(event.relatedTarget).data('objeto');
                var urlAction = "{{ route('imagen.destroy', ':id') }}".replace(':id', data.id_imagen);
                $(this).find('#formDelete').attr('action', urlAction);
                $(this).find('#delete_ruta_nombre').text(data.ruta.nombre_ruta);
                $(this).find('#delete_img_preview').attr('src', getImgUrl(data.url_imagen));
            });
        });

        // Select2 en modal crear
        $('#create').on('shown.bs.modal', function() {
            $('#id_ruta').select2({
                dropdownParent: $('#create')
            });
        });

        // Select2 en modales de edición dinámicos
        $('.modal').on('shown.bs.modal hidden.bs.modal', function(event) {
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
    </script>
@stop

