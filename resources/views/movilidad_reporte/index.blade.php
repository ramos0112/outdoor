<!--resources/views/movilidad_reporte.index.blade.php-->
@extends('layouts.admin-base')

@section('title', 'Manifiesto por Movilidad')

@section('content_header')
    <h1>MANIFIESTO DE PASAJEROS</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">Filtros de Búsqueda</h3>
            </div>
            <form action="">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group"><label>Fecha de Viaje</label><input type="date" name="fecha"
                                    class="form-control" required></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Seleccionar ruta</label><select name="id_ruta"
                                    class="form-control select2" required>
                                    <option value="">Seleccione una ruta...</option>
                                </select></div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"><label>Seleccionar Movilidad</label><select name="id_movilidad"
                                    class="form-control select2" required>
                                    <option value="">Seleccione una unidad...</option>
                                </select></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="text-right mb-3">
                    @can('reporte.descargas')
                    <button id="btnExcel" class="btn btn-success" title="Excel"><i class="fas fa-file-excel"></i></button>
                    <button id="btnPdf" class="btn btn-danger" title="PDF"><i class="fas fa-file-pdf"></i></button>
                    <button id="btnPrint" class="btn btn-info" title="Imprimir"><i class="fas fa-print"></i></button>
                    @endcan
                </div>
                <div class="table-responsive">
                    <table id="tablaManifiesto" class="table table-bordered table-striped">
                        <thead class="bg-dark text-white">
                            <tr>
                                <th>Guía</th>
                                <th>Ruta</th>
                                <th>Cliente</th>
                                <th>Teléfono</th>
                                <th>Estado Pago</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
@stop

@section('css')
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container .select2-selection--single {
            height: 38px !important;
        }

        /* Ocultamos los botones originales de DataTables porque usaremos los tuyos personalizados */
        .dt-buttons {
            display: none;
        }
    </style>
@stop

@section('js')
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
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });

            let tabla = $('#tablaManifiesto').DataTable({
                searching: true, // Cambiado a true para que la exportación funcione mejor
                paging: false,
                info: false,
                dom: 'Bfrtip', // Habilita los botones
                buttons: [{
                        extend: 'excelHtml5',
                        title: 'Manifiesto_Pasajeros'
                    },
                    {
                        extend: 'pdfHtml5',
                        title: function() {
                            return 'Ruta: ' +
                                $('select[name="id_ruta"] option:selected').text() +
                                ' - ' + 'Fecha: ' +
                                $('input[name="fecha"]').val();
                        },
                        orientation: 'portrait', // 👈 Vertical
                        pageSize: 'A4',
                        customize: function(doc) {

                            // Centrar el título
                            doc.styles.title = {
                                alignment: 'center',
                                fontSize: 14,
                                bold: true
                            };

                            // Centrar toda la tabla
                            doc.content[1].table.widths = Array(doc.content[1].table.body[0].length)
                                .fill('*');

                            doc.content[1].layout = {
                                hAlign: 'center'
                            };

                            // Márgenes más equilibrados
                            doc.pageMargins = [40, 60, 40, 60];
                        }
                    },

                    {
                        extend: 'print',
                        title: 'REPORTE DE TURISTAS',
                        customize: function(win) {
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).css('font-size', '12pt');
                        }
                    }
                ],
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                }
            });

            // VINCULAR TUS BOTONES PERSONALIZADOS
            $('#btnExcel').on('click', function() {
                tabla.button('.buttons-excel').trigger();
            });

            $('#btnPdf').on('click', function() {
                tabla.button('.buttons-pdf').trigger();
            });
            $('#btnPrint').on('click', () => tabla.button('.buttons-print').trigger());

            $('input[name="fecha"]').on('change', function() {
                let fecha = $(this).val();

                $('select[name="id_ruta"]').empty().append(
                    '<option value="">Seleccione una ruta...</option>');
                $('select[name="id_movilidad"]').empty().append(
                    '<option value="">Seleccione una unidad...</option>');
                tabla.clear().draw();

                if (!fecha) return;

                $.get("{{ url('/movilidad-reporte/rutas') }}", {
                    fecha: fecha
                }, function(data) {
                    $.each(data, function(i, ruta) {
                        $('select[name="id_ruta"]').append(
                            `<option value="${ruta.id_ruta}">${ruta.nombre_ruta}</option>`
                        );
                    });
                });
            });

            // PASO 2: Ruta → Movilidades
            $('select[name="id_ruta"]').on('change', function() {
                let id_ruta = $(this).val();
                // Obtener el valor de la fecha del input directamente
                let fecha = $('input[name="fecha"]').val();

                $('select[name="id_movilidad"]').empty().append(
                    '<option value="">Seleccione una unidad...</option>');
                tabla.clear().draw();

                // Validar que ambos existan antes de la petición
                if (!id_ruta || !fecha) return;

                $.get("{{ url('/movilidad-reporte/movilidades') }}", {
                    id_ruta: id_ruta, // Añadida la coma aquí
                    fecha: fecha // Ahora la variable fecha ya tiene valor
                }, function(data) {
                    $.each(data, function(i, mov) {
                        $('select[name="id_movilidad"]').append(
                            `<option value="${mov.id_movilidad}">
                            ${mov.ruta} Conductor-${mov.conductor} 
                </option>`
                        );
                    });
                });
            });

            // PASO 3: Movilidad → Manifiesto
            $('select[name="id_movilidad"]').on('change', function() {
                let id_movilidad = $(this).val();

                tabla.clear().draw();

                if (!id_movilidad) return;

                $.get("{{ url('/movilidad-reporte/manifiesto') }}", {
                    id_movilidad: id_movilidad
                }, function(response) {

                    tabla.clear();

                    $.each(response.data, function(i, row) {
                        // Formatear saldo con color rojo si debe dinero
                        let saldoBadge = row.saldo > 0 ?
                            `<span class="text-danger fw-bold">S/. ${row.saldo}</span>` :
                            `<span class="text-success">Pagado</span>`;

                        // Formatear estado con badge de AdminLTE
                        let estadoBadge = row.estado === 'Pagado' ?
                            `<span class="badge badge-success">${row.estado}</span>` :
                            `<span class="badge badge-warning">${row.estado}</span>`;

                        tabla.row.add([
                            response.guias,
                            row.nombre_ruta,
                            row.cliente,
                            row.telefono ?? '-',
                            estadoBadge,
                            saldoBadge
                        ]);
                    });

                    tabla.draw();
                });
            });
        });
    </script>
@stop

