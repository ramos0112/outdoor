<div class="modal fade" id="modalEliminar{{ $fecha->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $fecha->id }}" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="modalLabel{{ $fecha->id }}">Confirmar eliminación</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-body">
          <p><strong>¿Estás seguro de que deseas eliminar esta fecha disponible?</strong></p>
          <p>Ruta: <strong>{{ $fecha->ruta->nombre }}</strong></p>
          <p>Fecha: <strong>{{ $fecha->fecha }}</strong></p>
        </div>
        <div class="modal-footer">
          <form action="{{ route('fechas.destroy', $fecha->id) }}" method="POST">
            @csrf
            @method('DELETE')
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  