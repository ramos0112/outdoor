<div class="modal fade" id="modalAccionFecha" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalFechaTitulo" aria-hidden="true">
    <div class="modal-dialog">
        <form id="formEditarFecha" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header" id="modalFechaHeader">
                    <h5 class="modal-title" id="modalFechaTitulo">Editar Fecha Disponible</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="edit_id_fecha">

                    <div class="mb-3">
                        <label for="edit_id_ruta" class="form-label">Ruta:</label>
                        <div class="w-100">
                            <select style="width: 100%" name="id_ruta" id="edit_id_ruta" class="form-select" required>
                                <option value="" disabled>Seleccionar Ruta</option>
                                @foreach ($rutas as $ruta)
                                    <option value="{{ $ruta->id_ruta }}">{{ $ruta->nombre_ruta }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="edit_fecha" class="form-label">Fecha:</label>
                        <input type="date" class="form-control" name="fecha" id="edit_fecha" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" id="btnGuardarFecha" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </div>
        </form>
    </div>
</div>