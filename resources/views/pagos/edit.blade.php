<div class="modal fade" id="edit{{ $pago->id_pago }}" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pagos.update', $pago->id_pago) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editLabel">Editar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                        <input type="datetime-local" class="form-control" id="fecha_pago" name="fecha_pago" 
                               value="{{ \Carbon\Carbon::parse($pago->fecha_pago)->format('Y-m-d\TH:i') }}" required>
                    </div>
                    <!-- Otros campos del formulario aquí -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
