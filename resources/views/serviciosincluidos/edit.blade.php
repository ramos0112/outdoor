<div class="modal fade" id="edit{{ $servicio->id_servicio }}" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white" >
                <h1 class="modal-title fs-5" id="editLabel">Editar Servicio Incluido</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <form action="{{ route('servicios.update', $servicio->id_servicio) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="id_ruta_edit{{ $servicio->id_servicio }}" class="form-label">Ruta</label>
                        <select class="form-control" name="id_ruta" id="id_ruta_edit{{ $servicio->id_servicio }}" required>
                            <option value="" disabled selected>Seleccionar Ruta</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id_ruta }}" {{ $servicio->id_ruta == $ruta->id_ruta ? 'selected' : '' }}>
                                    {{ $ruta->nombre_ruta }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="servicio" class="form-label">Servicio</label>
                        <input type="text" class="form-control" name="servicio" value="{{ $servicio->servicio }}" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
