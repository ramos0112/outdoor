<!-- Modal de Confirmación -->
<div class="modal fade" id="confirmDelete{{ $imagen->id_imagen }}" tabindex="-1"
    aria-labelledby="confirmDeleteLabel{{ $imagen->id_imagen }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmDeleteLabel{{ $imagen->id_imagen }}">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar esta imagen? Esta acción no se puede deshacer.
            </div>
            <div class="modal-footer">
                <form action="{{ route('imageness.destroy', $imagen->id_imagen) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Sí, eliminar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </form>
            </div>
        </div>
    </div>
</div>
