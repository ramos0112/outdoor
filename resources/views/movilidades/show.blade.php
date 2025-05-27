@foreach ($movilidades as $movilidad)
    <div class="modal fade" id="show{{ $movilidad->id_movilidad }}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- En el modal de show, muestra los guías asignados -->

                <div class="modal-body">
                    <p><strong>ID:</strong> {{ $movilidad->id_movilidad }}</p>
                    <p><strong>capasidad:</strong> {{ $movilidad->capacidad }}</p>
                    <p><strong>Estado:</strong> {{ $movilidad->estado }}</p>
                    <p><strong>Guías asignados:</strong></p>
                    <ul>
                        @foreach ($movilidad->guias as $guia)
                            <li>{{ $guia->nombre }} {{ $guia->apellido }}</li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
