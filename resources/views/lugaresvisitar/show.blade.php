<!-- Modal Show -->
<div class="modal fade" id="show{{ $lugar->id_lugar }}" tabindex="-1" aria-labelledby="showLabel{{ $lugar->id_lugar }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="showLabel{{ $lugar->id_lugar }}">Detalles del Lugar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> {{ $lugar->id_lugar }}</p>
                <p><strong>Ruta:</strong> {{ $lugar->ruta->nombre_ruta }}</p>
                <p><strong>Nombre del Lugar:</strong> {{ $lugar->nombre_lugar }}</p>
                {{-- Agrega más detalles si los hay --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
