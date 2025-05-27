<div class="modal fade" id="edit{{ $movilidad->id_movilidad }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="editLabel{{ $movilidad->id_movilidad }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('movilidades.update', $movilidad->id_movilidad) }}" method="POST" id="movilidades-form-edit-{{ $movilidad->id_movilidad }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Editar Movilidad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Campo de Capacidad -->
                    <div class="mb-3">
                        <label for="capacidad" class="form-label">Capacidad</label>
                        <input type="number" class="form-control" id="capacidad" name="capacidad" value="{{ $movilidad->capacidad }}" required>
                    </div>

                    <!-- Campo de Estado -->
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="Disponible" {{ $movilidad->estado == 'Disponible' ? 'selected' : '' }}>Disponible</option>
                            <option value="Ocupado" {{ $movilidad->estado == 'Ocupado' ? 'selected' : '' }}>Ocupado</option>
                        </select>
                    </div>

                    <!-- Campo para asignar Guías -->
                    <div class="mb-3">
                        <label for="id_guia" class="form-label">Guías</label>
                        <select name="guias[]" id="id_guia_edit{{ $movilidad->id_movilidad }}" class="form-control" multiple required>
                            <option value="" disabled>Seleccionar Guías</option>
                            @foreach ($guias as $guia)
                                <option value="{{ $guia->id_guia }}" 
                                    @if(in_array($guia->id_guia, $movilidad->guias->pluck('id_guia')->toArray())) 
                                        selected 
                                    @endif>
                                    {{ $guia->nombre }} {{ $guia->apellido }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
