<!-- resources/views/fechasdisponible/create.blade.php --><!-- Modal -->
<div class="modal fade" id="create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('fechas.store') }}" method="POST" id="fechasdisponible-form-create ">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">Agregar Fechas Disponibles</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="mb-3">
                        <label for="id_ruta" class="form-label">Ruta:</label>
                        <div class="input-group has-validation">
                            <select style="width: 100%" name="id_ruta" id="id_ruta" 
                            aria-describedby="id_ruta"
                            class="form-select" required>
                                <option value="">Seleccionar Ruta</option>
                                @foreach ($rutas as $ruta)
                                    <option value="{{ $ruta->id_ruta }}">{{ $ruta->nombre_ruta }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="ruta">Seleccionar una rutaA.</div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="fecha" class="form-label">Fecha:</label>
                        <input type="date" name="fecha" id="fecha" class="form-control" required>
                        <div class="invalid-feedback" id ="fecha">Seleccionar una fecha.</div>
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
