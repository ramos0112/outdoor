
<!-- Modal -->
<div class="modal fade" id="edit{{ $imagen->id_imagen }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Modal title</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
             <!-- Formulario con padding interno -->
             <form action="{{ route('imageness.update', $imagen->id_imagen) }}" method="POST"
                enctype="multipart/form-data" class="p-3">
                @csrf
                @method('PUT')

                <!-- Ruta -->
                <div class="mb-3">
                    <label for="id_ruta_edit" class="form-label">Ruta</label>
                    <select class="form-control select2" name="id_ruta" required>
                        @foreach ($rutas as $ruta)
                            <option value="{{ $ruta->id_ruta }}"
                                {{ $imagen->id_ruta == $ruta->id_ruta ? 'selected' : '' }}>
                                {{ $ruta->nombre_ruta }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Opción 1: URL externa -->
                <div class="mb-3">
                    <label for="url_imagen" class="form-label">URL externa (opcional)</label>
                    <input type="text" class="form-control" name="url_imagen" placeholder="https://..."
                        value="">
                </div>

                <!-- Opción 2: Subir nueva imagen -->
                <div class="mb-3">
                    <label for="imagen_archivo" class="form-label">Subir nueva imagen (opcional)</label>
                    <input type="file" class="form-control" name="imagen_archivo" accept="image/*">
                </div>

                <!-- Imagen actual -->
                <div class="mb-3">
                    <label class="form-label">Imagen actual:</label><br>
                    <img src="{{ asset($imagen->url_imagen) }}" alt="Imagen actual" width="100"
                        class="img-thumbnail">
                </div>

                <!-- Botones -->
                <div class="modal-footer px-0">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
      </div>
    </div>
  </div>