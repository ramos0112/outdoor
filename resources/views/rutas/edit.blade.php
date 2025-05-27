<!-- Modal -->
<div class="modal fade" id="edit{{ $ruta->id_ruta }}" tabindex="-1" aria-labelledby="editLabel{{ $ruta->id_ruta }}" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('rutas.update', $ruta->id_ruta) }}" method="POST" id="ruta-form-edit{{ $ruta->id_ruta }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLabel{{ $ruta->id_ruta }}">Editar Ruta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <!-- Campos del formulario -->
                    <div class="mb-3">
                        <label for="nombre_ruta{{ $ruta->id_ruta }}" class="form-label">Nombre Ruta</label>
                        <input type="text" class="form-control" name="nombre_ruta" id="nombre_ruta{{ $ruta->id_ruta }}" value="{{ $ruta->nombre_ruta }}" required>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion_general{{ $ruta->id_ruta }}" class="form-label">Descripción</label>
                        <input type="text" class="form-control" name="descripcion_general" id="descripcion_general{{ $ruta->id_ruta }}" value="{{ $ruta->descripcion_general }}">
                    </div>
                    <div class="mb-3">
                        <label for="tipo{{ $ruta->id_ruta }}" class="form-label">Tipo</label>
                        <input type="text" class="form-control" name="tipo" id="tipo{{ $ruta->id_ruta }}" value="{{ $ruta->tipo }}">
                    </div>
                    <div class="mb-3">
                        <label for="precio_regular{{ $ruta->id_ruta }}" class="form-label">Precio Regular</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" name="precio_regular" id="precio_regular{{ $ruta->id_ruta }}" value="{{ $ruta->precio_regular }}" step="0.01" required>
                        </div>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="descuento{{ $ruta->id_ruta }}" class="form-label">Descuento</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" name="descuento" id="descuento{{ $ruta->id_ruta }}" value="{{ $ruta->descuento }}" step="0.01" required>
                        </div>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                    <div class="mb-3">
                        <label for="precio_actual{{ $ruta->id_ruta }}" class="form-label">Precio Actual</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" name="precio_actual" id="precio_actual{{ $ruta->id_ruta }}" value="{{ $ruta->precio_actual }}" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="dificultad{{ $ruta->id_ruta }}" class="form-label">Dificultad</label>
                        <input type="text" class="form-control" name="dificultad" id="dificultad{{ $ruta->id_ruta }}" value="{{ $ruta->dificultad }}">
                    </div>
                    <div class="mb-3">
                        <label for="estado{{ $ruta->id_ruta }}" class="form-label">Estado</label>
                        <select class="form-control" name="estado" id="estado{{ $ruta->id_ruta }}" required>
                            <option value="Activo" {{ $ruta->estado == 'Activo' ? 'selected' : '' }}>Activo</option>
                            <option value="Inactivo" {{ $ruta->estado == 'Inactivo' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // Función para calcular el precio actual
    function calcularPrecioActualEdit(id) {
        const precioRegular = parseFloat(document.getElementById('precio_regular' + id).value) || 0;
        const descuento = parseFloat(document.getElementById('descuento' + id).value) || 0;
        const precioActual = (precioRegular - descuento).toFixed(2);

        document.getElementById('precio_actual' + id).value = precioActual;
    }

    // Inicializamos el cálculo cuando se cargue el modal
    document.addEventListener('DOMContentLoaded', function() {
        const id = '{{ $ruta->id_ruta }}';
        calcularPrecioActualEdit(id);

        // Agregar eventos para el cálculo de precio actual
        document.getElementById('precio_regular' + id).addEventListener('input', function() {
            calcularPrecioActualEdit(id);
        });
        document.getElementById('descuento' + id).addEventListener('input', function() {
            calcularPrecioActualEdit(id);
        });

        // Validación del formulario de edición
        const form = document.getElementById('ruta-form-edit' + id);
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();  // Evita el envío del formulario si no es válido
                form.classList.add('was-validated');  // Muestra los mensajes de validación
            }
        });
    });
</script>
