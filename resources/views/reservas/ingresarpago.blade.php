<!-- resources/views/reservas/ingresarpago.blade.php -->
<!-- Modal para ingresar pago -->
<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form id="formPago" method="POST" action="{{ route('pagos.store') }}">
        @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPagoLabel">Registrar Nuevo Pago</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
            <label for="id_reserva" class="form-label">Reserva ID</label>
            <input type="text" class="form-control" id="modal_id_reserva" name="id_reserva" readonly>

            </div>
            <div class="mb-3">
              <label for="modal_ruta" class="form-label">Ruta</label>
              <input type="text" class="form-control" id="modal_ruta" readonly>
            </div>
  
            <div class="mb-3">
              <label for="metodo_pago" class="form-label">Método de Pago</label>
              <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                <option value="">Seleccionar método</option>
                <option value="Efectivo">Efectivo</option>
                <option value="Yape">Yape</option>
                <option value="Plin">Plin</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Otro">Otro</option>
              </select>
            </div>
  
            <div class="mb-3">
              <label for="monto_pagado" class="form-label">Monto Pagado</label>
              <input type="number" step="0.01" class="form-control" name="monto_pagado" id="monto_pagado" required>
            </div>
  
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Registrar Pago</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          </div>
        </div>
      </form>
    </div>
  </div>
  