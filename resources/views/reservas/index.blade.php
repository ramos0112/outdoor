@extends('adminlte::page')

@section('title', 'Gestion de Reservas')

@section('content_header')
    <h2>GESTIÓN DE RESERVAS</h2>
@stop

@section('content')
    <div class="row">
        <div class="card">
            <div class="container py-4">
                <!-- Buscador de DNI -->
                <div class="row mb-3 align-items-center">
                    <!-- elementos de la fila de filtros fecha y dni-->
                    <div class="col-md-8">
                        <!-- Fila con label de Fecha y input de Fecha junto al campo de búsqueda -->
                        <div class="d-flex flex-column flex-md-row align-items-start">
                            <!-- Label y campo para filtar por fecha -->
                            <div class="mb-3 mb-md-0 me-3">
                                <label for="fecha_disponible" class="form-label mb-0">Fecha de turs</label>
                                <input type="date" class="form-control w-auto" id="fecha_disponible"
                                value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            </div>
                            <!-- Label y campo de búsqueda -->
                            <div class="w-100">
                                <label for="dni" class="form-label mb-0">Buscar por DNI</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control w-100" id="dni"
                                        placeholder="Ingrese número de documento">
                                    <!-- Icono de búsqueda pegado al input -->
                                    <button id="buscarBtn" class="btn btn-primary ms-2">
                                        <i class="fa fa-search"></i> <!-- Icono de búsqueda -->
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Columna para el botón "Ingresar nuevo pago" -->
                    <div class="col-md-4 d-flex justify-content-md-end mt-3 mt-md-0">
                        <button class="btn btn-success w-auto w-md-auto" data-bs-toggle="modal" data-bs-target="#modalPago">
                            Ingresar pago
                        </button>
                    </div>
                </div>
                <!-- Detalle de la Reserva -->
                <div id="resultadoReserva" class="mt-4">
                    <h4>Detalle de la Reserva</h4>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>ID Reserva:</label>
                                <input type="text" class="form-control" id="id_reserva" readonly>
                            </div>
                            <div class="mb-2">
                                <label>Ruta:</label>
                                <input type="text" class="form-control" id="ruta" readonly>
                            </div>
                            <div class="mb-2">
                                <label>Movilidad(es):</label>
                                <input type="text" class="form-control" id="movilidad" readonly>
                            </div>
                            <div class="mb-2">
                                <label>Guía(s):</label>
                                <input type="text" class="form-control" id="guias" readonly>
                            </div>
                            <div class="mb-2">
                                <label>Fecha de Reserva:</label>
                                <input type="text" class="form-control" id="fecha_reserva" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>Cliente(s):</label>
                                <input type="text" class="form-control" id="clientes" readonly>
                            </div>
                            <div class="mb-2">
                                <label>Cantidad de Personas:</label>
                                <input type="text" class="form-control" id="cantidad_personas" readonly>
                            </div>
                            <div class="mb-2">
                                <label>Fecha del Tour:</label>
                                <input type="text" class="form-control" id="fecha_tour" readonly>
                            </div>
                            <div class="mb-2">
                                <label>Saldo:</label>
                                <input type="text" class="form-control" id="saldo" readonly>
                            </div>
                            <div class="mb-2">
                                <label>Estado:</label>
                                <input type="text" class="form-control" id="estado" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-2">
                                <label>Total:</label>
                                <input type="text" class="form-control" id="precio_total" readonly>
                            </div>

                            <!-- Historial de Pagos -->
                            <div id="historialPagos" class="mt-4">
                                <h5>Historial de Pagos</h5>
                                <!-- Contenedor con tamaño fijo y barra de desplazamiento -->
                                <div class="overflow-auto" style="max-height: 250px;">
                                    <!-- Usamos max-height con overflow-auto -->
                                    <table class="table table-bordered text-center">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Monto</th>
                                                <th>Método</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tablaHistorialPagos">
                                            <!-- Filas dinámicas se agregarán con JavaScript -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Tabla de Reservas -->
            <div class="table-responsive mt-3">
                <h2>Hisorial de las ultimas Reservas actulizadas</h2>
                <br>
                <table id="tablaPagos" class="table table-bordered table-striped text-center w-100">
                    <thead class="table-dark">
                        <tr>
                            <th>ID Reserva</th>
                            <th>Ruta</th>
                            <th>Movilidad</th>
                            <th>DNI</th>
                            <th>Cliente(s)</th>
                            <th>FechadelTour</th>
                            <th>Total</th>
                            <th>Saldo</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody id="tablaReservas">
                        @foreach ($reservas as $reserva)
                            <tr>
                                <td>{{ $reserva->id_reserva }}</td>
                                <td>{{ $reserva->fechaDisponible->ruta->nombre_ruta ?? 'Sin ruta' }}</td>
                                <td>
                                    @foreach ($reserva->movilidads as $movilidad)
                                        {{ $movilidad->id_movilidad }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($reserva->clientes as $cliente)
                                        {{ $cliente->numero_documento }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ $reserva->clientes->pluck('nombre')->join(', ') }}</td>
                                <td>{{ $reserva->fechaDisponible->fecha }}</td>
                                <td>{{ 'S/. ' . number_format($reserva->precio_total, 2) }}</td>
                                <td>{{ 'S/. ' . number_format($reserva->saldo, 2) }}</td>
                                <td>{{ $reserva->estado }}</td>
                            </tr>
                        @endforeach
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

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $('#buscarBtn').click(function() {
            const dni = $('#dni').val();
            const fecha = $('#fecha_disponible').val(); 

            // Limpiar los campos de resultado y ocultar
            $('#resultadoReserva').show(); // Mostrar el bloque de resultado
            $('#id_reserva, #ruta, #movilidad, #guias, #clientes, #fecha_reserva, #fecha_tour, #cantidad_personas, #precio_total, #saldo, #estado')
                .val(''); // Limpiar campos principales

            // Limpiar los campos del modal cuando se haga una nueva búsqueda
            $('#modal_id_reserva, #modal_ruta').val('');

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
                        // Llenar los campos del modal
                        $('#modal_id_reserva').val(data.id_reserva);
                        $('#modal_ruta').val(data.fecha_disponible?.ruta?.nombre_ruta ?? 'Sin ruta');

                        // Llenar los campos con los datos de la reserva
                        $('#id_reserva').val(data.id_reserva);
                        $('#ruta').val(data.fecha_disponible?.ruta?.nombre_ruta ?? 'Sin ruta');
                        $('#movilidad').val(data.movilidads.map(m => m.id_movilidad).join(', '));
                        $('#guias').val(data.movilidads.flatMap(m => m.guias.map(g => g.nombre + ' ' + g
                            .apellido)).join(', '));
                        $('#clientes').val(data.clientes.map(c => c.nombre + ' ' + c.apellido).join(
                            ', '));
                        $('#fecha_reserva').val(data.fecha_reserva);
                        $('#fecha_tour').val(data.fecha_disponible?.fecha ?? '');
                        $('#cantidad_personas').val(data.cantidad_personas);
                        $('#precio_total').val('S/. ' + parseFloat(data.precio_total).toFixed(2));
                        $('#saldo').val('S/. ' + parseFloat(data.saldo).toFixed(2));
                        $('#estado').val(data.estado);
                        // Llenar tabla de pagos
                        $('#tablaHistorialPagos').empty(); // Limpiar antes

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
                            $('#tablaHistorialPagos').append(`
                                <tr><td colspan="4">No hay pagos registrados.</td></tr>
                            `);
                        }

                    } else {
                        // Si no se encuentra la reserva, mostramos un mensaje de error
                        alert('Reserva no encontrada para ese DNI.');

                        // Limpiar el modal si no se encuentra reserva
                        $('#modal_id_reserva, #modal_ruta').val('');
                    }
                },
                error: function() {
                    toastr.error('Reserva no encontrada para ese DNI y la fecha seleccionada.');
                }

            });
        });

        $('#formPago').submit(function(event) {
            event.preventDefault();

            const monto = parseFloat($('#monto_pagado').val());
            const saldoStr = $('#saldo').val().replace(/[S\/\.\s]/g, '');
            const saldo = parseFloat(saldoStr);

            if (monto > saldo) {
                toastr.error('El monto ingresado excede el saldo restante. Intenta con un valor menor.');
                return;
            }

            const formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        toastr.success('Pago registrado correctamente.');
                        $('#modalPago input, #modalPago select').val('');
                        $('#dni').val('');
                        $('#resultadoReserva input').val('');
                        $('#modalPago').modal('hide');

                        const nuevaReserva = response.reserva;
                        const nuevaFila = `
                    <tr id="reserva_${nuevaReserva.id_reserva}">
                        <td>${nuevaReserva.id_reserva}</td>
                        <td>${nuevaReserva.fechaDisponible?.ruta?.nombre_ruta ?? 'Sin ruta'}</td>
                        <td>${nuevaReserva.movilidads.map(m => m.id_movilidad).join(', ')}</td>
                        <td>${nuevaReserva.clientes.map(c => c.numero_documento).join(', ')}</td>
                        <td>${nuevaReserva.clientes.map(c => c.nombre + ' ' + c.apellido).join(', ')}</td>
                        <td>${nuevaReserva.fecha_reserva}</td>
                        <td>S/. ${nuevaReserva.precio_total.toFixed(2)}</td>
                        <td>S/. ${nuevaReserva.saldo.toFixed(2)}</td>
                        <td>${nuevaReserva.estado}</td>
                    </tr>
                `;

                        const filaExistente = $(`#reserva_${nuevaReserva.id_reserva}`).length > 0;
                        if (filaExistente) {
                            $(`#reserva_${nuevaReserva.id_reserva}`).replaceWith(nuevaFila);
                        } else {
                            $('#tablaReservas').prepend(nuevaFila);
                        }
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
