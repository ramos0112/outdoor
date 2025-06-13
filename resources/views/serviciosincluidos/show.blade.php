<!-- Modal Show -->
<div class="modal fade" id="show{{ $servicio->id_servicio }}" tabindex="-1" aria-labelledby="showLabel{{ $servicio->id_servicio }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="showLabel{{ $servicio->id_servicio }}">Detalles del Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> {{ $servicio->id_servicio }}</p>
                <p><strong>Ruta:</strong> {{ $servicio->ruta->nombre_ruta }}</p>
                <p><strong>Servicio:</strong> {{ $servicio->servicio }}</p>
                {{-- Puedes agregar más campos si los hay --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
