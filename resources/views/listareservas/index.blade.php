<!-- resources/views/listareservas/index.blade.php -->
@extends('layouts.admin-base')

@section('title', 'Reservas')

@section('content_header')
    <h1>LISTA DE RESERVAS</h1>

@stop
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                </div>
                <div class="d-flex" style="gap: 5px;">
                    <button id="btnExcel" class="btn btn-success" title="Excel"><i class="fas fa-file-excel"></i></button>
                    <button id="btnPdf" class="btn btn-danger" title="PDF"><i class="fas fa-file-pdf"></i></button>
                    <button id="btnPrint" class="btn btn-info text-white" title="Imprimir"><i
                            class="fas fa-print"></i></button>
                    @can('reservas.crear')
                        <button class="btn btn-primary ml-2" data-bs-toggle="modal" data-bs-target="#modalCreate"><i
                                class="fas fa-plus"></i> Agregar</button>
                    @endcan
                </div>
            </div>
            <div class="table-responsive">
                <table class="  table table-bordered table-striped text-center" id="reservasTable">
                    <thead class="table-dark">
                        <tr>
                            <th>ID RESERVA</th>
                            <th>RUTA</th>
                            <th>Movil. Cond</th>
                            <th>GUIA(S)</th>
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
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        .dt-buttons {
            display: none;
        }

        /* Oculta botones por defecto de DT */
        .table {
            font-size: 0.9rem;
        }

        #reservasTable td {
            vertical-align: middle !important;
            /* Centra el contenido verticalmente */
            white-space: nowrap;
            /* Evita que los nombres largos se partan en dos líneas si no es necesario */
        }

        .text-left {
            text-align: left !important;
            /* Los nombres de clientes se leen mejor alineados a la izquierda */
        }

        .required::after {
            content: " *";
            color: red;
            font-weight: bold;
        }
    </style>

@stop

@section('js')
    @include('partials.toastr')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            /// ================= DATATABLE =================
            // ================= DATATABLE SERVER-SIDE =================
            let tabla = $('#reservasTable').DataTable({
                processing: true,
                serverSide: true, // 👈 ACTIVAMOS EL MODO SERVIDOR
                ajax: {
                    url: "{{ route('listareservas.index') }}", // Llama al mismo método index del controlador
                    type: 'GET'
                },
                dom: 'Blfrtip', // Devolvemos la 'l' y la 'f' para que aparezcan los filtros nativos
                buttons: [
                    { extend: 'excelHtml5', title: 'Reporte_Reservas' },
                    { extend: 'pdfHtml5', title: 'REPORTE RESERVAS OUTDOOR', orientation: 'landscape', pageSize: 'LEGAL' },
                    { 
                        extend: 'print', 
                        title: 'LISTA DE RESERVAS',
                        customize: function(win) {
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).css('font-size', '12pt');
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                paging: true,
                ordering: false, // Mantenemos desactivado el ordenamiento JS para respetar el orden descendente del servidor
                searching: true,
                responsive: true,
                autoWidth: false,
                columnDefs: [
                    { targets: [4], className: 'text-left' } // Alinea la columna de clientes a la izquierda
                ]
            });

            // Eventos de botones personalizados
            $('#btnExcel').on('click', () => tabla.button('.buttons-excel').trigger());
            $('#btnPdf').on('click', () => tabla.button('.buttons-pdf').trigger());
            $('#btnPrint').on('click', () => tabla.button('.buttons-print').trigger());

            // ================= FECHAS POR RUTA =================
            $('#id_ruta').on('change', function() {
                var rutaId = $(this).val();
                let $fechaSelect = $('#id_fecha');
                $fechaSelect.empty().append('<option value="">Cargando fechas...</option>');

                if (rutaId) {
                    $.get('/api/fechas-por-ruta/' + rutaId, function(data) {
                        $fechaSelect.empty().append(
                            '<option value="">Seleccione una fecha</option>');
                        $.each(data, (i, fecha) => {
                            $fechaSelect.append(
                                `<option value="${fecha.id_fecha}">${fecha.fecha}</option>`
                            );
                        });
                    });
                } else {
                    $fechaSelect.empty().append('<option value="">Seleccione una ruta primero</option>');
                }
            });
            // ================= SELECT2 (MODAL FIX) =================
            $('#modalCreate').on('shown.bs.modal', function() {

                $('#id_ruta, #id_movilidad').select2({
                    dropdownParent: $('#modalCreate'),
                    width: '100%',
                    placeholder: 'Seleccione una opción',
                    allowClear: true
                });

            });

            // Limpiar al cerrar modal
            $('#modalCreate').on('hidden.bs.modal', function() {
                $('#id_ruta, #id_movilidad').val(null).trigger('change');
                $('#id_fecha').empty().append('<option value="">Seleccione una ruta</option>');
            });


            // Lógica de búsqueda de cliente por DNI
            $('#numero_documento').on('change', function() {
                let numero = $(this).val();
                if (numero.trim() === '') return;

                $.get(`/buscar-cliente/${numero}`, function(data) {
                    $('#tipo_documento').val(data.tipo_documento);
                    $('#nombre').val(data.nombre);
                    $('#apellido').val(data.apellido);
                    $('#fecha_nacimiento').val(data.fecha_nacimiento);
                    $('#email').val(data.email);
                    $('#telefono').val(data.telefono);
                    $('#pais').val(data.pais);
                    $('#region').val(data.region);
                    $('#ciudad').val(data.ciudad);
                }).fail(function() {
                    // Limpiar campos si falla
                    $('.modal-body input').not('#numero_documento').val('');
                });
            });
        });
    </script>

@stop

