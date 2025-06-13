<!-- Modal de edición -->
<div class="modal fade" id="edit{{ $movilidad->id_movilidad }}" tabindex="-1" aria-labelledby="editLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('movilidades.update', $movilidad->id_movilidad) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLabel">Editar Movilidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Similar a la creación, pero con valores predefinidos -->
                    <div class="mb-3">
                        <label for="ruta" class="form-label">Ruta</label>
                        <input type="text" class="form-control" name="ruta" id="ruta" value="{{ $movilidad->ruta }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_movilidad" class="form-label">Tipo de Movilidad</label>
                        <input type="text" class="form-control" name="tipo_movilidad" id="tipo_movilidad" value="{{ $movilidad->tipo_movilidad }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacidad" class="form-label">Capacidad</label>
                        <input type="number" class="form-control" name="capacidad" id="capacidad" value="{{ $movilidad->capacidad }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-control" required>
                            <option value="Disponible" {{ $movilidad->estado == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="Ocupado" {{ $movilidad->estado == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                        </select>
                    </div>

                    <!-- Select de guías -->
                    <div class="mb-3">
                        <label for="id_guia_edit{{ $movilidad->id_movilidad }}" class="form-label">Seleccionar Guías</label>
                        <select id="id_guia_edit{{ $movilidad->id_movilidad }}" name="guias[]" class="form-control" multiple >
                            @foreach($guias as $guia)
                                <option value="{{ $guia->id_guia }}" {{ in_array($guia->id_guia, $movilidad->guias->pluck('id_guia')->toArray()) ? 'selected' : '' }}>{{ $guia->nombre }} {{ $guia->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Movilidad</button>
                </div>
            </div>
        </form>
    </div>
</div>
