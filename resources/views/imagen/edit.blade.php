<div class="modal fade" id="modalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h1 class="modal-title fs-5" id="editLabel">Editar Imagen de Ruta</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" method="POST" id="formEdit" enctype="multipart/form-data" class="p-3">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="edit_id_ruta" class="form-label">Ruta</label>
                        <select class="form-control select2" name="id_ruta" id="edit_id_ruta" required>
                            <option value="" disabled>Seleccionar Ruta</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id_ruta }}">
                                    {{ $ruta->nombre_ruta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="edit_url_imagen" class="form-label">URL externa (opcional)</label>
                        <input type="text" class="form-control" name="url_imagen" id="edit_url_imagen" placeholder="https://...">
                    </div>

                    <div class="mb-3">
                        <label for="imagen_archivo" class="form-label">Subir nueva imagen (opcional)</label>
                        <input type="file" class="form-control" name="imagen_archivo" accept="image/*">
                    </div>

                    <div class="mb-3 text-center">
                        <label class="form-label d-block">Imagen actual:</label>
                        <img id="edit_img_actual" src="" alt="Imagen actual" width="150" class="img-thumbnail">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-warning">Actualizar Imagen</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>