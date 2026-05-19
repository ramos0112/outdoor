@extends('layouts.admin-base')

@section('title', 'Historial de Actividad')

@section('content_header')
    <h1>HISTORIAL DE ACTIVIDAD (AUDITORÍA)</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-dark">
            <h3 class="card-title">Filtros de Búsqueda</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('logs.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <label>Tipo de Actividad</label>
                        <select name="tipo" class="form-control">
                            <option value="">-- Todos --</option>
                            <!--<option value="Gestión de Reservas"
                                    {{ request('tipo') == 'Gestión de Reservas' ? 'selected' : '' }}>Reservas</option>
                                <option value="Pagos" {{ request('tipo') == 'Pagos' ? 'selected' : '' }}>Pagos</option>
                                <option value="Asignación Logística"
                                    {{ request('tipo') == 'Asignación Logística' ? 'selected' : '' }}>Logística</option> -->
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Usuario</label>
                        <select name="usuario" class="form-control select2">
                            <option value="">-- Todos --</option>
                            @foreach ($usuarios as $user)
                                <option value="{{ $user->id }}" {{ request('usuario') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label>Desde</label>
                        <input type="date" name="desde" class="form-control" value="{{ request('desde') }}">
                    </div>

                    <div class="col-md-2">
                        <label>Hasta</label>
                        <input type="date" name="hasta" class="form-control" value="{{ request('hasta') }}">
                    </div>

                    <div class="col-md-2 d-flex align-items-end" style="gap: 5px;">
                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter"></i></button>
                        <a href="{{ route('logs.index') }}" class="btn btn-secondary w-100"><i class="fas fa-undo"></i></a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Usuario</th>
                            <th>Módulo</th>
                            <th>Descripción</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>{{ $activity->created_at->format('d/m/Y H:i') }}</td>
                                <td><span
                                        class="badge badge-outline-secondary">{{ $activity->causer->name ?? 'Sistema' }}</span>
                                </td>
                                <td><b class="text-primary">{{ $activity->log_name }}</b></td>
                                <td class="text-left">
                                    @if ($activity->description === 'created')
                                        <span class="text-success"><i class="fas fa-plus-circle"></i> Nuevo registro
                                            creado</span>
                                    @elseif($activity->description === 'updated')
                                        <span class="text-warning"><i class="fas fa-edit"></i> Modificación de datos</span>
                                    @elseif($activity->description === 'deleted')
                                        <span class="text-danger"><i class="fas fa-trash"></i> Registro eliminado</span>
                                    @else
                                        {{ $activity->description }}
                                    @endif

                                    {{-- Si guardamos el mensaje amigable en el controlador, aparecerá aquí --}}
                                    <div class="small text-muted">ID afectado: #{{ $activity->subject_id }}</div>
                                </td>
                                <td>
                                    <button class="btn btn-xs btn-info" onclick='showDetails(@json($activity->properties))'>
                                        <i class="fas fa-eye"></i> Detalles
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">No se encontraron registros con esos filtros.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer clearfix">
            {{ $activities->links() }}
        </div>
    </div>

    {{-- Modal simple para ver cambios --}}
    <div class="modal fade" id="modalChanges" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-black">
                    <h5 class="modal-title">Detalle de Modificación</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body">
                    <pre id="jsonViewer" style="background: #f4f4f4; padding: 10px; border-radius: 5px;"></pre>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        #jsonViewer {
            background: white !important;
            /* Cambiamos el fondo oscuro por blanco para las tablas */
            color: #333 !important;
            max-height: 400px;
            overflow-y: auto;
        }

        #jsonViewer table th {
            font-size: 0.8rem;
            text-align: center;
        }
    </style>
@stop

@section('js')
    <script>
        function showDetails(properties) {
            const viewer = document.getElementById('jsonViewer');
            let html = '';

            if (properties.attributes && properties.old) {
                html += '<table class="table table-sm table-bordered">';
                html += '<thead class="bg-light"><tr><th>Campo</th><th>Antes</th><th>Después</th></tr></thead>';
                html += '<tbody>';

                // Recorremos las propiedades modificadas
                for (let key in properties.attributes) {
                    let oldVal = properties.old[key] || 'N/A';
                    let newVal = properties.attributes[key];

                    // Omitir timestamps si no quieres verlos
                    if (key === 'updated_at' || key === 'created_at') continue;

                    html += `<tr>
                <td class="text-uppercase"><strong>${key.replace('_', ' ')}</strong></td>
                <td class="text-danger">${oldVal}</td>
                <td class="text-success">${newVal}</td>
            </tr>`;
                }
                html += '</tbody></table>';
            } else {
                // Si es una creación o datos sin estructura 'old', mostramos formato llave-valor
                html += '<ul>';
                for (let key in properties) {
                    html += `<li><strong>${key}:</strong> ${JSON.stringify(properties[key])}</li>`;
                }
                html += '</ul>';
            }

            viewer.innerHTML = html;
            var myModal = new bootstrap.Modal(document.getElementById('modalChanges'));
            myModal.show();
        }
    </script>
@stop

