<div class="modal fade" id="create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content"> <!-- Agregado -->
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Agregar Imagen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <form action="{{ route('imageness.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body"> <!-- Agregado -->
                    <div class="mb-3">
                        <label for="id_ruta" class="form-label">Ruta</label>
                        <select class="form-control select2" name="id_ruta" required>
                            <option value="" disabled selected>Seleccionar Ruta</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id_ruta }}">{{ $ruta->nombre_ruta }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="url_imagen" class="form-label">URL de la Imagen (opcional)</label>
                        <input type="text" class="form-control" name="url_imagen" placeholder="https://...">
                    </div>

                    <div class="mb-3">
                        <label for="imagen_archivo" class="form-label">Subir Imagen (opcional)</label>
                        <input type="file" class="form-control" name="imagen_archivo" accept="image/*">
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
