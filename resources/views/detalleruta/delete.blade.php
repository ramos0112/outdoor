<div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formDeleteDetalleRuta">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title text-dark">¿Estás seguro?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Estás a punto de eliminar el siguiente detalle de ruta:</p>
                    <ul>
                        <li><strong>Ruta:</strong> <span id="delete_ruta"></span></li>
                        <li><strong>Descripción:</strong> <span id="delete_descripcion"></span></li>
                    </ul>
                    <p class="text-danger"><strong>¡Esta acción no se puede deshacer!</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </form>
    </div>
</div>
