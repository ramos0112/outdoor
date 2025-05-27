<div class="modal fade" id="create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('movilidades.store') }}" method="POST" id="movilidades-form-create">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Agregar Movilidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Campo de Capacidad -->
                    <div class="mb-3">
                        <label for="capacidad" class="form-label">Capacidad</label>
                        <input type="number" class="form-control" id="capacidad" name="capacidad" required>
                    </div>

                    <!-- Campo de Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="Disponible">Disponible</option>
                            <option value="Ocupado">Ocupado</option>
                        </select>
                    </div>

                    <!-- Campo para asignar Guías -->
                    <div class="mb-3">
                        <label for="id_guia" class="form-label">Guías</label>
                        <select name="guias[]" id="id_guia" class="form-control" multiple required>
                            @foreach ($guias as $guia)
                                <option value="{{ $guia->id_guia }}">{{ $guia->nombre }} {{ $guia->apellido }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
