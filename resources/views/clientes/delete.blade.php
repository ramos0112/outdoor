<div class="modal fade" id="modalDelete" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" id="deleteForm">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Eliminar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Seguro que deseas eliminar a <strong id="deleteNombre"></strong>?</p>
                    <p><strong>Documento:</strong> <span id="deleteDocumento"></span></p>
                    <p><strong>Email:</strong> <span id="deleteEmail"></span></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" type="submit">Eliminar</button>
                </div>
            </div>
        </form>
    </div>
</div>
