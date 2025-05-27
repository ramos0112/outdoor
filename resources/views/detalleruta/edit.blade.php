<div class="modal fade" id="modalEdit" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="formEditDetalleRuta">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Detalle de Ruta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_id_ruta">Ruta</label>
                        <select name="id_ruta" id="edit_id_ruta" class="form-control" style="width: 100%" required>
                            <option value="" disabled>Seleccionar Ruta</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id_ruta }}">{{ $ruta->nombre_ruta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="edit_descripcion" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
