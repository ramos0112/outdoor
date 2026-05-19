<div class="modal fade" id="modalDelete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="formDelete">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center">
                    <p>¿Estás seguro de eliminar esta imagen de la ruta <strong id="delete_ruta_nombre"></strong>?</p>
                    <img id="delete_img_preview" src="" width="150" class="img-thumbnail">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>