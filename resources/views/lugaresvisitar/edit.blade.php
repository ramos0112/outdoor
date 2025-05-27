<!-- Modal Editar Lugar -->
<div class="modal fade" id="edit{{ $lugar->id_lugar }}" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="editLabel">Editar Lugar de Visita</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <form action="{{ route('lugares.update', $lugar->id_lugar) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="id_ruta_edit{{ $lugar->id_lugar }}" class="form-label">Ruta</label>
                        <select class="form-control" name="id_ruta" id="id_ruta_edit{{ $lugar->id_lugar }}" required>
                            <option value="" disabled selected>Seleccionar Ruta</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id_ruta }}" {{ $lugar->id_ruta == $ruta->id_ruta ? 'selected' : '' }}>
                                    {{ $ruta->nombre_ruta }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="nombre_lugar_edit" class="form-label">Nombre del Lugar</label>
                        <input type="text" class="form-control" name="nombre_lugar" id="nombre_lugar_edit" value="{{ $lugar->nombre_lugar }}" required>
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
