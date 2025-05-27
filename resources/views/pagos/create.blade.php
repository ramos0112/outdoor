<div class="modal fade" id="create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('pagos.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Agregar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="id_reserva" class="form-label">Reserva ID</label>
                        <input type="text" class="form-control" id="id_reserva" name="id_reserva" required>
                    </div>
                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label">Método de Pago</label>
                        <input type="text" class="form-control" id="metodo_pago" name="metodo_pago" required>
                    </div>
                    <div class="mb-3">
                        <label for="monto_pagado" class="form-label">Monto Pagado</label>
                        <input type="number" step="0.01" class="form-control" id="monto_pagado" name="monto_pagado" required>
                    </div>
                    <div class="mb-3">
                        <label for="fecha_pago" class="form-label">Fecha de Pago</label>
                        <input type="datetime-local" class="form-control" id="fecha_pago" name="fecha_pago" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-success">Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>
