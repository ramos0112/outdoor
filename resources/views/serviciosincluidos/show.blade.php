<!-- Modal Show -->
<div class="modal fade" id="modalShow" tabindex="-1" aria-labelledby="showLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="showLabel">Detalles del Servicio</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong><span id="show_id_servicio"></span></p>
                <p><strong>Ruta:</strong> <span id="show_ruta"></span></p>
                <p><strong>Servicio:</strong> <span id="show_servicio"></span></p>
                {{-- Puedes agregar más campos si los hay --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
