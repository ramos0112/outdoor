<!-- Modal Show Pago -->
<div class="modal fade" id="show{{ $pago->id_pago }}" tabindex="-1" aria-labelledby="showLabel{{ $pago->id_pago }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="showLabel{{ $pago->id_pago }}">Detalles del Pago</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> {{ $pago->id_pago }}</p>
                <p><strong>ID de Reserva:</strong> {{ $pago->id_reserva }}</p>
                <p><strong>Método de Pago:</strong> {{ $pago->metodo_pago }}</p>
                <p><strong>Monto Pagado:</strong> S/. {{ number_format($pago->monto_pagado, 2) }}</p>
                <p><strong>Fecha de Pago:</strong> {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}</p>
                {{-- Puedes agregar más campos si los necesitas --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
