@extends('layouts.admin-base')
@section('title', 'Fechas Disponibles')
@section('content_header')
    <h1>Fechas Disponibles de una ruta</h1>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                @can('fechas.crear')
                <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#create">
                    <i class="fas fa-plus"></i> Agregar
                </button>
                @endcan
            </div>

            <div class="table-responsive mt-3">
                <table id="tablaFechas" class="table table-bordered table-striped w-100">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="text-center" scope="col">ID</th>
                            <th class="text-center" scope="col">Ruta</th>
                            <th class="text-center" scope="col">Fecha</th>
                            <th class="text-center" scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        </tbody>
                </table>
            </div>
        </div>

        {{-- Modales Únicos Reutilizables --}}
        @include('fechasdisponible.create')
        @include('fechasdisponible.edit')
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // 1. Inicializar DataTable Server-Side
            var table = $('#tablaFechas').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('fechas.index') }}",
                    type: 'GET'
                },
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                lengthMenu: [10, 25, 50, 100],
                autoWidth: false,
                responsive: true,
                order: [[0, 'desc']]
            });

            // Inicializar Select2 al abrir el modal de creación
            $('#create').on('shown.bs.modal', function () {
                $('#id_ruta').select2({ dropdownParent: $('#create') });
            });

            // Inicializar / Destruir Select2 al abrir el modal dinámico de Edición/Ver
            $('#modalAccionFecha').on('shown.bs.modal', function () {
                $('#edit_id_ruta').select2({ dropdownParent: $('#modalAccionFecha') });
            }).on('hidden.bs.modal', function () {
                if ($('#edit_id_ruta').hasClass('select2-hidden-accessible')) {
                    $('#edit_id_ruta').select2('destroy');
                }
            });

            // 2. Interceptor de acciones Ver / Editar para el Modal Único Global
            $(document).on('click', '.btn-accion-fecha', function() {
                const action = $(this).data('action');
                const id = $(this).data('id');
                const idRuta = $(this).data('id_ruta');
                const fecha = $(this).data('fecha');

                // Inyectar datos en el formulario único
                $('#edit_id_fecha').val(id);
                $('#edit_fecha').val(fecha);
                $('#edit_id_ruta').val(idRuta).trigger('change');

                // Construir URL dinámica para el update
                let urlUpdate = "{{ route('fechas.update', ':id') }}".replace(':id', id);
                $('#formEditarFecha').attr('action', urlUpdate);

                if (action === 'show') {
                    $('#modalFechaTitulo').text('Detalle de Fecha Disponible (Solo Lectura)');
                    $('#modalFechaHeader').removeClass('bg-warning text-dark').addClass('bg-info text-white');
                    $('#formEditarFecha select, #formEditarFecha input').prop('disabled', true);
                    $('#btnGuardarFecha').hide();
                } else {
                    $('#modalFechaTitulo').text('Editar Fecha Disponible');
                    $('#modalFechaHeader').removeClass('bg-info text-white').addClass('bg-warning text-dark');
                    $('#formEditarFecha select, #formEditarFecha input').prop('disabled', false);
                    $('#btnGuardarFecha').show();
                }
            });

            // 3. Envío por AJAX - Crear Fecha
            $('#formCrearFecha').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.message);
                            $('#formCrearFecha')[0].reset();
                            $('#id_ruta').val('').trigger('change');
                            $('#create').modal('hide');
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errores = xhr.responseJSON.errors;
                            Object.keys(errores).forEach(key => toastr.error(errores[key][0]));
                        } else {
                            toastr.error('Error al guardar el registro.');
                        }
                    }
                });
            });

            // 4. Envío por AJAX - Editar Fecha
            $('#formEditarFecha').submit(function (e) {
                e.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function (res) {
                        if (res.success) {
                            toastr.success(res.message);
                            $('#modalAccionFecha').modal('hide');
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errores = xhr.responseJSON.errors;
                            Object.keys(errores).forEach(key => toastr.error(errores[key][0]));
                        } else {
                            toastr.error('Error al actualizar el registro.');
                        }
                    }
                });
            });

            // 5. ELIMINACIÓN DIRECTA Y ESTÉTICA CON SWEETALERT2 (Sin necesidad de modal HTML adicional)
            $(document).on('click', '.btn-eliminar-fecha', function () {
                const id = $(this).data('id');
                const ruta = $(this).data('nombre_ruta');
                const fecha = $(this).data('fecha');

                Swal.fire({
                    title: '¿Estás seguro?',
                    html: `Vas a eliminar la fecha disponible:<br><b>Ruta:</b> ${ruta}<br><b>Fecha:</b> ${fecha}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: '<i class="bi bi-trash"></i> Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        let urlDelete = "{{ route('fechas.destroy', ':id') }}".replace(':id', id);

                        $.ajax({
                            url: urlDelete,
                            method: 'POST',
                            data: {
                                _method: 'DELETE',
                                _token: "{{ csrf_token() }}"
                            },
                            success: function (res) {
                                if (res.success) {
                                    Swal.fire({
                                        title: '¡Eliminado!',
                                        text: res.message,
                                        icon: 'success',
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                    table.ajax.reload(null, false);
                                }
                            },
                            error: function () {
                                toastr.error('No se pudo completar la eliminación debido a un error en el servidor.');
                            }
                        });
                    }
                });
            });
        });
    </script>
@stop
