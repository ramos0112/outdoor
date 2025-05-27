<!-- Modal -->
<div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('rutas.store') }}" method="POST" id="ruta-form">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createLabel">Agregar Ruta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="mb-3">
                        <label for="nombre_ruta" class="form-label">Nombre Ruta</label>
                        <input type="text" class="form-control" name="nombre_ruta" id="nombre_ruta" required>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion_general" class="form-label">Descripción</label>
                        <input type="text" class="form-control" name="descripcion_general" id="descripcion_general" required>

                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo</label>
                        <input type="text" class="form-control" name="tipo" id="tipo" required>
                    </div>
                    <div class="mb-3">
                        <label for="precio_regular" class="form-label">Precio Regular</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" id="precio_regular" name="precio_regular" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="descuento" class="form-label">Descuento</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" id="descuento" name="descuento" step="0.01" required>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label for="precio_actual" class="form-label">Precio Actual</label>
                        <div class="input-group">
                            <span class="input-group-text">S/.</span>
                            <input type="number" class="form-control" id="precio_actual" name="precio_actual" readonly>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="dificultad" class="form-label">Dificultad</label>
                        <input type="text" class="form-control" name="dificultad" id="dificultad" required>
                    </div>
                    <div class="mb-3">
                        <label for="estado" class="form-label">Estado</label>
                        <select class="form-control" name="estado" id="estado" required>
                            <option value="">Seleccione un estado</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                        <div class="invalid-feedback">Este campo es obligatorio.</div>
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

<script>
    // Función para calcular el precio actual
    function calcularPrecioActual() {
        const precioRegular = parseFloat(document.getElementById('precio_regular').value) || 0;
        const descuento = parseFloat(document.getElementById('descuento').value) || 0;

        // Calculamos el precio actual restando el descuento directo
        const precioActual = precioRegular - descuento;

        // Asignamos el valor al campo "precio_actual"
        document.getElementById('precio_actual').value = precioActual.toFixed(2);
    }

    // Agregar eventos para que el cálculo se haga cuando el usuario cambie el precio o descuento
    document.getElementById('precio_regular').addEventListener('input', calcularPrecioActual);
    document.getElementById('descuento').addEventListener('input', calcularPrecioActual);

    // Inicializamos el cálculo al cargar la página
    window.onload = calcularPrecioActual;

    // Validación del formulario
    document.getElementById('ruta-form').addEventListener('submit', function(event) {
        // Verificamos si todos los campos requeridos están llenos
        const form = this;
        const isValid = form.checkValidity();

        if (!isValid) {
            event.preventDefault();  // Evita el envío del formulario si no es válido
            form.classList.add('was-validated');  // Muestra los mensajes de validación
        }
    });
</script>
