@extends('adminlte::page')

@section('title', 'Asignación Masiva de Movilidad')

@section('content_header')
    <h1>Asignar Reservas a una Movilidad</h1>
@stop

@section('content')
    <div class="container mt-4">

        {{-- Formulario --}}
        <form method="POST" action="{{ route('reservasmovilidad.store') }}">
            @csrf

            <!-- Controles arriba -->
            <div class="row mb-3 d-flex flex-column flex-md-row align-items-md-center">
                <div class="col-md-4 mb-2">
                    <select name="id_movilidad" id="id_movilidad" class="form-control select2" required>
                        <option value="">Selecciona una Movilidad</option>
                        @foreach ($movilidades as $movilidad)
                            <option value="{{ $movilidad->id_movilidad }}">
                                -Ruta: {{ $movilidad->ruta }} -{{ $movilidad->tipo_movilidad }} -Cap:
                                {{ $movilidad->capacidad }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 mb-2">
                    <label id="totalPersonas" class="fw-bold text-primary">Total: 0</label>
                </div>
                <div class="col-md-4 text-md-end mb-2">
                    @can('reservas.asignar')
                        <button type="submit" class="btn btn-primary w-100 w-md-auto py-2 px-3 fs-6">
                            <i class="bi bi-check-circle me-2"></i> Asignar
                        </button>
                    @endcan
                </div>
            </div>

            <!-- Botón seleccionar todo para móviles -->
            <div class="mb-2 d-md-none">
                <button type="button" id="selectAllBtn" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="bi bi-check2-square"></i> Seleccionar/Deseleccionar Todo
                </button>
            </div>

            <!-- Tabla -->
            <div class="table-responsive">
                <table id="reservasMasivas" class="table table-bordered table-striped text-center">
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
                        @foreach ($reservas as $reserva)
                            <tr>
                                <td>
                                    <input type="checkbox" class="reserva-check" name="reservas[]"
                                        value="{{ $reserva->id_reserva }}"
                                        data-personas="{{ $reserva->cantidad_personas }}">
                                </td>
                                <td>{{ $reserva->id_reserva }}</td>
                                <td>{{ $reserva->fechaDisponible->ruta->nombre_ruta ?? 'Sin ruta' }}</td>
                                <td>{{ $reserva->cantidad_personas }}</td>
                                <td>{{ $reserva->fechaDisponible->fecha ?? 'Sin fecha' }}</td>
                                <td>
                                    @if ($reserva->estado === 'pendiente')
                                        <span class="text-warning"><i class="bi bi-clock-fill"></i> Pendiente</span>
                                    @elseif($reserva->estado === 'confirmado')
                                        <span class="text-success"><i class="bi bi-check-circle-fill"></i> Confirmado</span>
                                    @else
                                        <span class="text-secondary"><i class="bi bi-question-circle-fill"></i>
                                            {{ $reserva->estado }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
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
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <!-- Agrega el CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@stop

@section('js')
    @include('partials.toastr')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <!-- Agrega el JS de Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            // Inicializar DataTable
            $('#reservasMasivas').DataTable({
                language: {
                    url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
                },
                responsive: true
            });
            // Inicializar Select2 en el select de movilidad con Bootstrap y AdminLTE
            $('#id_movilidad').select2({
                placeholder: "Selecciona una Movilidad",
                allowClear: true,
                width: '100%' // Para asegurar que el select2 ocupe todo el ancho disponible
            });

            // Checkbox principal (versión desktop)
            $('#checkAll').on('click', function() {
                $('.reserva-check').prop('checked', this.checked).trigger('change');
            });

            // Botón "Seleccionar todo" (móviles)
            $('#selectAllBtn').on('click', function() {
                const total = $('.reserva-check').length;
                const checked = $('.reserva-check:checked').length;
                const newState = checked !== total;
                $('.reserva-check').prop('checked', newState).trigger('change');
            });

            // Contador total de personas
            function actualizarContador() {
                let total = 0;
                $('.reserva-check:checked').each(function() {
                    total += parseInt($(this).data('personas')) || 0;
                });
                $('#totalPersonas').text('Total: ' + total);
            }

            $(document).on('change', '.reserva-check', actualizarContador);
        });
    </script>

    {{-- Toastr mensajes desde Laravel --}}
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
