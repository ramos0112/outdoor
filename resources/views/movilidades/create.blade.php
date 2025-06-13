<!-- Modal de creación -->
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('movilidades.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">Crear Movilidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Ruta, tipo de movilidad, capacidad y estado -->
                    <div class="mb-3">
                        <label for="ruta" class="form-label">Ruta</label>
                        <input type="text" class="form-control" name="ruta" id="ruta" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo_movilidad" class="form-label">Tipo de Movilidad</label>
                        <input type="text" class="form-control" name="tipo_movilidad" id="tipo_movilidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="capacidad" class="form-label">Capacidad</label>
                        <input type="number" class="form-control" name="capacidad" id="capacidad" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select name="estado" id="estado" class="form-control" required>
                            <option value="Disponible">Disponible</option>
                            <option value="Ocupado">Ocupado</option>
                        </select>
                    </div>
                    
                    <!-- Select de guías -->
                    <div class="mb-3">
                        <label for="id_guia" class="form-label">Seleccionar Guías</label>
                        <select id="id_guia" name="id_guia[]" class="form-control" multiple >
                            @foreach($guias as $guia)
                                <option value="{{ $guia->id_guia }}">{{ $guia->nombre }} {{ $guia->apellido }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Movilidad</button>
                </div>
            </div>
        </form>
    </div>
</div>
