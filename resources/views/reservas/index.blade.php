@extends('layouts.admin-base')

@section('title', 'Gestion de Reservas')

@section('content_header')
    <h2>GESTIÓN DE RESERVAS</h2>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="container py-4">
                <div class="row mb-3 align-items-center">
                    <div class="col-md-8">
                        <div class="d-flex flex-column flex-md-row align-items-start">
                            <div class="mb-3 mb-md-0 me-3">
                                <label for="fecha_disponible" class="form-label mb-0">Fecha de tours</label>
                                <input type="date" class="form-control w-auto" id="fecha_disponible" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            </div>
                            <div class="w-100">
                                <label for="dni" class="form-label mb-0">Buscar por DNI</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control w-100" id="dni" placeholder="Ingrese número de documento">
                                    <button id="buscarBtn" class="btn btn-primary ms-2">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @can('pagos.crear')
                        <div class="col-md-4 d-flex justify-content-md-end mt-3 mt-md-0">
                            <button class="btn btn-success w-auto w-md-auto" data-bs-toggle="modal" data-bs-target="#modalPago">
                                Ingresar pago
                            </button>
                        </div>
                    @endcan
                </div>

                <div id="resultadoReserva" class="mt-4">
                    <h4>Detalle de la Reserva</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2"><label>ID Reserva:</label><input type="text" class="form-control" id="id_reserva" readonly></div>
                            <div class="mb-2"><label>Ruta:</label><input type="text" class="form-control" id="ruta" readonly></div>
                            <div class="mb-2"><label>Movilidad -- Conductor:</label><input type="text" class="form-control" id="movilidad" readonly></div>
                            <div class="mb-2"><label>Guía:</label><input type="text" class="form-control" id="guias" readonly></div>
                            <div class="mb-2"><label>Fecha de Reserva:</label><input type="text" class="form-control" id="fecha_reserva" readonly></div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2"><label>Cliente(s):</label><input type="text" class="form-control" id="clientes" readonly></div>
                            <div class="mb-2"><label>Cantidad de Personas:</label><input type="text" class="form-control" id="cantidad_personas" readonly></div>
                            <div class="mb-2"><label>Fecha del Tour:</label><input type="text" class="form-control" id="fecha_tour" readonly></div>
                            <div class="mb-2"><label>Saldo:</label><input type="text" class="form-control" id="saldo" readonly></div>
                            <div class="mb-2"><label>Total:</label><input type="text" class="form-control" id="precio_total" readonly></div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2"><label>Estado:</label><input type="text" class="form-control" id="estado" readonly></div>
                            
                            <div id="historialPagos" class="mt-4">
                                <h5>Historial de Pagos</h5>
                                <div class="overflow-auto" style="max-height: 250px;">
                                    <table class="table table-bordered text-center">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Monto</th>
                                                <th>Método</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaHistorialPagos">
                                            </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <h2>Historial reservas: Actualizadas</h2>
                <br>
                <table id="tablaGestionReservas" class="table table-bordered table-striped text-center w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Reserva</th>
                            <th>Ruta</th>
                            <th>Movilidad</th>
                            <th>DNI</th>
                            <th>Cliente(s)</th>
                            <th>Fecha del Tour</th>
                            <th>Total</th>
                            <th>Saldo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        </tbody>
                </table>
            </div>
        </div>
    </div>
    @include('reservas.ingresarpago')
@stop

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar DataTables Server-Side en la tabla inferior
            $('#tablaGestionReservas').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('gestionreservas.index') }}",
                    type: 'GET'
                },
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: false,
                searching: true,
                responsive: true,
                autoWidth: false
            });
        });

        // Evento Buscar por DNI y Fecha
        $('#buscarBtn').click(function() {
            ejecutarBusqueda();
        });

        function ejecutarBusqueda() {
            const dni = $('#dni').val();
            const fecha = $('#fecha_disponible').val();

            $('#resultadoReserva').show();
            $('#id_reserva, #ruta, #movilidad, #guias, #clientes, #fecha_reserva, #fecha_tour, #cantidad_personas, #precio_total, #saldo, #estado').val('');
            $('#modal_id_reserva, #modal_ruta').val('');
            $('#tablaHistorialPagos').empty();

            $.ajax({
                url: '{{ route('gestionreservas.buscar') }}',
                method: 'POST',
                data: {
                    dni: dni,
                    fecha: fecha,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    if (data) {
                        $('#modal_id_reserva').val(data.id_reserva);
                        $('#modal_ruta').val(data.fecha_disponible?.ruta?.nombre_ruta ?? 'Sin ruta');

                        $('#id_reserva').val(data.id_reserva);
                        $('#ruta').val(data.fecha_disponible?.ruta?.nombre_ruta ?? 'Sin ruta');
                        $('#movilidad').val(data.movilidads.map(m => `${m.id_movilidad} -- ${m.conductor}`).join(', '));
                        $('#guias').val(data.movilidads.flatMap(m => m.guias.map(g => g.nombre + ' ' + g.apellido)).join(', '));
                        $('#clientes').val(data.clientes.map(c => c.nombre + ' ' + c.apellido).join(', '));
                        $('#fecha_reserva').val(data.fecha_reserva);
                        $('#fecha_tour').val(data.fecha_disponible?.fecha ?? '');
                        $('#cantidad_personas').val(data.cantidad_personas);
                        $('#precio_total').val('S/. ' + parseFloat(data.precio_total).toFixed(2));
                        $('#saldo').val('S/. ' + parseFloat(data.saldo).toFixed(2));
                        $('#estado').val(data.estado);

                        if (data.pagos && data.pagos.length > 0) {
                            data.pagos.forEach(pago => {
                                $('#tablaHistorialPagos').append(`
                                    <tr>
                                        <td>${pago.fecha_pago}</td>
                                        <td>S/. ${parseFloat(pago.monto_pagado).toFixed(2)}</td>
                                        <td>${pago.metodo_pago}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            $('#tablaHistorialPagos').append('<tr><td colspan="3">No hay pagos registrados.</td></tr>');
                        }
                    }
                },
                error: function() {
                    toastr.error('Reserva no encontrada para ese DNI y la fecha seleccionada.');
                }
            });
        }

// Envío del Formulario del Modal de Pago (CORREGIDO)
$('#formPago').submit(function(event) {
    event.preventDefault();

    const monto = parseFloat($('#monto_pagado').val());
    const saldoStr = $('#saldo').val().replace(/[S\/\.\s]/g, '');
    const saldo = parseFloat(saldoStr);

    if (monto > saldo) {
        toastr.error('El monto ingresado excede el saldo restante. Intenta con un valor menor.');
        return;
    }

    $.ajax({
        url: $(this).attr('action'),
        method: 'POST',
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                toastr.success('Pago registrado correctamente.');
                
                // 1. Limpiar inputs de monto/método del modal y ocultarlo
                $('#monto_pagado').val('');
                $('#metodo_pago').val('');
                $('#modalPago').modal('hide');

                // 2. REFRESCAR LA TABLA GENERAL inferior automáticamente por AJAX
                if ($.fn.DataTable.isDataTable('#tablaGestionReservas')) {
                    $('#tablaGestionReservas').DataTable().ajax.reload(null, false); 
                }

                // 3. Volver a ejecutar la búsqueda interna para actualizar los campos fijos de arriba y el historial
                ejecutarBusqueda();
            } else {
                toastr.error(response.message || 'Hubo un error al registrar el pago.');
            }
        },
        error: function(xhr) {
            if (xhr.status === 422 && xhr.responseJSON?.message) {
                toastr.error(xhr.responseJSON.message);
            } else {
                toastr.error('Error al procesar el pago.');
            }
        }
    });
});
    </script>
@stop
