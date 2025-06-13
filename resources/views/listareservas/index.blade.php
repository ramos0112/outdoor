<!-- resources/views/listareservas/index.blade.php -->
@extends('adminlte::page')

@section('title', 'Reservas')

@section('content_header')
    <h1>LISTA DE RESERVAS</h1>

@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                @can('reservas.crear')
                    <button class="btn btn-success ms-auto mb-3" data-bs-toggle="modal" data-bs-target="#modalCreate"><i class="fas fa-plus"></i> Agregar</button>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="  table table-bordered table-striped text-center" id="reservasTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID RESERVA</th>
                            <th>RUTA</th>
                            <th>Movilidad</th>
                            <th>Guía(s)</th>
                            <th>CLIENTE</th>
                            <th>DNI</th>
                            <th>FECHA DE RESERVA</th>
                            <th>FECHA DEL TOUR</th>
                            <th>CAN. PERSONAS</th>
                            <th>S/. TOTAL</th>
                            <th>SALDO</th>
                            <th>ESTADO</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($listareservas as $listareserva)
                            <tr>
                                <td>{{ $listareserva->id_reserva }}</td>
                                <td>{{ $listareserva->fechaDisponible->ruta->nombre_ruta ?? 'Sin ruta' }}</td>
                                <td>
                                    @foreach ($listareserva->movilidads as $movilidad)
                                        {{ $movilidad->id_movilidad ?? 'Sin placa' }}<br>
                                    @endforeach
                                </td>

                                <!-- Guías -->
                                <td>
                                    @foreach ($listareserva->movilidads as $movilidad)
                                        @foreach ($movilidad->guias as $guia)
                                            {{ $guia->nombre }}{{ $guia->apellido }}
                                        @endforeach
                                    @endforeach
                                </td>

                                <td>
                                    <!-- Clientes -->
                                    @foreach ($listareserva->clientes as $cliente)
                                        <p> {{ $cliente->nombre }}{{ $cliente->apellido }}</p>
                                    @endforeach
                                </td>
                                <td>
                                    <!-- Clientes -->
                                    @foreach ($listareserva->clientes as $cliente)
                                        {{ $cliente->numero_documento }}
                                    @endforeach
                                </td>
                                <td>{{ \Carbon\Carbon::parse($listareserva->fecha_reserva)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($listareserva->fechaDisponible->fecha ?? null)->format('d/m/Y') }}
                                </td>
                                <td>{{ $listareserva->cantidad_personas }}</td>
                                <td>S/. {{ number_format($listareserva->precio_total, 2) }}</td>
                                <td>S/. {{ number_format($listareserva->saldo, 2) }}</td>
                                <td>{{ $listareserva->estado }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @include('listareservas.create')
    </div>
@stop

@section('css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Incluir el CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js"></script>
    <!-- DataTables JS para funcionalidades avanzadas en tablas -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <!-- DataTables con estilo Bootstrap 5 -->
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Incluir el JS de Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#reservasTable').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: true,
                searching: true,
                responsive: true,
                autoWidth: false, // Ajusta el ancho de las columnas automáticamente
                order: [
                    [0, 'desc']
                ]
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                dropdownParent: $('#modalCreate')
            });

            $('#id_ruta').on('change', function() {
                var rutaId = $(this).val();
                $('#id_fecha').empty().append('<option value="">Cargando fechas...</option>');

                if (rutaId) {
                    $.ajax({
                        url: '/api/fechas-por-ruta/' + rutaId,
                        type: 'GET',
                        success: function(data) {
                            $('#id_fecha').empty().append(
                                '<option value="">Seleccione una fecha</option>');
                            $.each(data, function(i, fecha) {
                                $('#id_fecha').append('<option value="' + fecha
                                    .id_fecha + '">' + fecha.fecha + '</option>');
                            });
                        }
                    });
                } else {
                    $('#id_fecha').empty().append('<option value="">Seleccione una ruta primero</option>');
                }
            });
        });

        $(document).ready(function() {
            $('#numero_documento').on('change', function() {
                let numero = $(this).val();

                if (numero.trim() !== '') {
                    $.ajax({
                        url: `/buscar-cliente/${numero}`,
                        type: 'GET',
                        success: function(data) {
                            $('#tipo_documento').val(data.tipo_documento);
                            $('#nombre').val(data.nombre);
                            $('#apellido').val(data.apellido);
                            $('#fecha_nacimiento').val(data.fecha_nacimiento);
                            $('#email').val(data.email);
                            $('#telefono').val(data.telefono);
                            $('#pais').val(data.pais);
                            $('#region').val(data.region);
                            $('#ciudad').val(data.ciudad);
                        },
                        error: function() {
                            // Limpiar campos si no se encuentra cliente
                            $('#tipo_documento').val('');
                            $('#nombre').val('');
                            $('#apellido').val('');
                            $('#fecha_nacimiento').val('');
                            $('#email').val('');
                            $('#telefono').val('');
                            $('#pais').val('');
                            $('#region').val('');
                            $('#ciudad').val('');
                        }
                    });
                }
            });
        });
    </script>

@stop
