<div class="summary-card shadow-lg">
    <h5 class="adventure-h2 text-white text-center mb-4 ">
        Resumen de tu Viaje
    </h5>

    <div class="d-flex justify-content-between mb-2">
        <span>Ruta:</span>
        <span class="fw-bold ">{{ $ruta->nombre_ruta }}</span>
    </div>
    <div class="d-flex justify-content-between mb-2">
        <span>Precio Regular:</span>
        <span class="fw-bold">S/. {{ $ruta->precio_regular }}</span>
    </div>
    <div class="d-flex justify-content-between mb-2">
        <span>Precio Oferta:</span>
        <span class="text-success fw-bold">S/. {{ $ruta->precio_actual }}</span>
    </div>
    <div class="d-flex justify-content-between mb-4">
        <span>Turistas:</span>
        <span id="cantidadPersonasTexto" class="text-center">1 </span>
    </div>
    <div class="d-flex justify-content-between mb-4">
        <span>Fecha de viaje:</span>
        <span id="fechaSeleccionada">Selecciona fecha</span>
    </div>

    <div class="border-top border-secondary pt-3 mt-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="fs-5">Total Reserva:</span>
            <span class="fs-4 fw-bold" id="totalPago">S/. 0</span>
        </div>
        <div class="p-3 rounded mt-3" style="background: rgba(40, 167, 69, 0.1); border: 1px dashed var(--exp-green);">
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-success fw-bold">PAGAR:</span>
                <span class="fs-2 fw-bold text-success" id="total50" style="text-shadow: none;">S/. 0</span>
            </div>
            <p class="small text-muted mb-0 mt-2 text-center text-white-50">
               <samp class="text-white">Paga el saldo restante el día del tour.</samp>
            </p>
        </div>
    </div>
</div>
