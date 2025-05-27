<!--modal eliminar movilidad-->
<div class="modal fade" id="delete{{ $movilidad->id_movilidad }}" tabindex="-1" aria-labelledby="deleteLabel{{ $movilidad->id_movilidad }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('movilidades.destroy', $movilidad->id_movilidad) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteLabel{{ $movilidad->id_movilidad }}">Eliminar Movilidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estas seguro de que deseas eliminar la movilidad <strong>{{ $movilidad->id_movilidad }}</strong>?</p>
                    <p><strong>Capacidad:</strong> {{ $movilidad->capacidad }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </form>
    </div>
</div>