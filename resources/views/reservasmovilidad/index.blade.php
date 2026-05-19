@extends('layouts.admin-base')

@section('title', 'Asignación Masiva de Movilidad')

@section('content_header')
    <h1>Asignar Reservas a una Movilidad</h1>
@stop

@section('content')
    <div class="container mt-4">
        {{-- Formulario --}}
        <form id="formAsignacionMasiva" method="POST" action="{{ route('reservasmovilidad.store') }}">
            @csrf

            <div class="row mb-3 d-flex flex-column flex-md-row align-items-md-center">
                <div class="col-md-4 mb-2">
                    <select name="id_movilidad" id="id_movilidad" class="form-control select2" required>
                        <option value="">Selecciona una Movilidad</option>
                        @foreach ($movilidades as $movilidad)
                            <option value="{{ $movilidad->id_movilidad }}">
                                -Ruta: {{ $movilidad->ruta }} -{{ $movilidad->conductor }} -Cap: {{ $movilidad->capacidad }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label id="totalPersonas" class="fw-bold text-primary">Total: 0 pasajeros</label>
                </div>
                <div class="col-md-4 text-md-end mb-2">
                    @can('reservas.asignar')
                        <button type="submit" class="btn btn-primary w-100 w-md-auto py-2 px-3 fs-6">
                            <i class="bi bi-check-circle me-2"></i> Asignar Seleccionados
                        </button>
                    @endcan
                </div>
            </div>

            <div class="mb-2 d-md-none">
                <button type="button" id="selectAllBtn" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-check2-square"></i> Seleccionar/Deseleccionar la Página
                </button>
            </div>

            <div id="inputsSeleccionadosOcultos"></div>

            <div class="table-responsive">
                <table id="reservasMasivas" class="table table-bordered table-striped text-center w-100">
                    <thead class="table-dark">
                        <tr>
                            <th><input type="checkbox" id="checkAll" class="d-none d-md-inline-block"></th>
                            <th>ID Reserva</th>
                            <th>Ruta</th>
                            <th>Personas</th>
                            <th>Fecha del Tour</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </form>
    </div>
@stop

@section('css')
    <style>
        @media (max-width: 768px) {

            .form-select,
            .btn,
            label {
                font-size: 1rem;
            }

            #reservasMasivas th,
            #reservasMasivas td {
                font-size: 0.875rem;
                padding: 0.5rem;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Objeto persistente para guardar las reservas seleccionadas [id_reserva: cantidad_personas]
            let reservasSeleccionadas = {};

            // Inicializar DataTables Server-Side
            const table = $('#reservasMasivas').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('reservasmovilidad.index') }}",
                    type: 'GET'
                },
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                order: [],
                autoWidth: false,
                responsive: true,
                // Cada vez que la tabla dibuje una página nueva, verificamos si hay elementos previamente seleccionados
                drawCallback: function() {
                    $('.reserva-check').each(function() {
                        const id = $(this).val();
                        if (reservasSeleccionadas[id] !== undefined) {
                            $(this).prop('checked', true);
                        }
                    });
                    actualizarCheckAllState();
                }
            });

            // Inicializar Select2
            $('#id_movilidad').select2({
                placeholder: "Selecciona una Movilidad",
                allowClear: true,
                width: '100%'
            });

            // Capturar evento cuando se marca/desmarca un checkbox individual
            $(document).on('change', '.reserva-check', function() {
                const id = $(this).val();
                const personas = parseInt($(this).data('personas')) || 0;

                if (this.checked) {
                    reservasSeleccionadas[id] = personas;
                } else {
                    delete reservasSeleccionadas[id];
                }

                actualizarContadorGlobal();
                actualizarCheckAllState();
            });

            // Checkbox principal (Desktop) - Afecta solo a la página actual
            $('#checkAll').on('click', function() {
                $('.reserva-check').prop('checked', this.checked).trigger('change');
            });

            // Botón móvil - Seleccionar/Deseleccionar la página actual
            $('#selectAllBtn').on('click', function() {
                const totalPage = $('.reserva-check').length;
                const checkedPage = $('.reserva-check:checked').length;
                const newState = checkedPage !== totalPage;
                $('.reserva-check').prop('checked', newState).trigger('change');
            });

            // Función para calcular la cantidad total de personas seleccionadas entre todas las páginas
            function actualizarContadorGlobal() {
                let total = 0;
                for (const id in reservasSeleccionadas) {
                    total += reservasSeleccionadas[id];
                }
                $('#totalPersonas').text('Total: ' + total + ' pasajeros');
            }

            // Sincronizar el estado del checkbox maestro superior
            function actualizarCheckAllState() {
                const totalPage = $('.reserva-check').length;
                const checkedPage = $('.reserva-check:checked').length;
                $('#checkAll').prop('checked', totalPage > 0 && checkedPage === totalPage);
            }

            // Al enviar el formulario, inyectamos las variables seleccionadas de otras páginas
            // Al enviar el formulario, inyectamos las variables seleccionadas de forma limpia (Sin duplicados)
            $('#formAsignacionMasiva').submit(function(e) {
                const contenedorOculto = $('#inputsSeleccionadosOcultos');
                contenedorOculto.empty(); // Limpiar entradas antiguas

                // Usamos un Set para asegurar que bajo ninguna circunstancia se repitan los IDs
                const keysUnicas = [...new Set(Object.keys(reservasSeleccionadas))];

                if (keysUnicas.length === 0) {
                    toastr.error('Debe seleccionar al menos una reserva para asignar.');
                    e.preventDefault();
                    return false;
                }

                // Generar inputs ocultos limpios y únicos para pasar los IDs seleccionados al Request de Laravel
                keysUnicas.forEach(id => {
                    contenedorOculto.append(
                    `<input type="hidden" name="reservas[]" value="${id}">`);
                });
            });
        });
    </script>

    {{-- Notificaciones Toastr --}}
    @if ($errors->any())
        <script>
            @foreach ($errors->all() as $error)
                toastr.error("{{ $error }}");
            @endforeach
        </script>
    @endif

    @if (session('success'))
        <script>
            toastr.success("{{ session('success') }}");
        </script>
    @endif
@stop

