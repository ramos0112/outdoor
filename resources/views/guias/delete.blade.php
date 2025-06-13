<div class="modal fade" id="delete{{ $guia->id_guia }}" tabindex="-1" aria-labelledby="deleteLabel{{ $guia->id_guia }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('guias.destroy', $guia->id_guia) }}" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header bg-red text-white">
                    <h5 class="modal-title" id="deleteLabel{{ $guia->id_guia }}">Eliminar Guía</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Mostrar el nombre del guía que se va a eliminar -->
                    <p>¿Está seguro de que desea eliminar al guía <strong>{{ $guia->nombre }} {{ $guia->apellido }}</strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </form>
    </div>
</div>
