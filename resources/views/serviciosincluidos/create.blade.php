<div class="modal fade" id="create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Crear Nuevo Servicio Incluido</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <form action="{{ route('servicios.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="id_ruta" class="form-label">Ruta</label>
                        <select class="form-control" name="id_ruta" id="id_ruta" required>
                            <option value="" disabled selected>Seleccionar Ruta</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id_ruta }}">{{ $ruta->nombre_ruta }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="servicio" class="form-label">Servicio</label>
                        <input type="text" class="form-control" name="servicio" id="servicio" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
