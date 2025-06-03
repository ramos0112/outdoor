<!-- Contenedor Derecho: Resumen y Pago -->
<div class="col-md-4 p-4 rounded border border-secondary bg-black bg-opacity-50 ms-md-5">
    <h5 class="fw-bold text-center mb-3 text-light">Resumen de Ruta</h5>
    <!-- Resumen del precio y la ruta -->
    <div class="bg-dark p-3 rounded border border-light mb-4">
        <h4>Precio Regular: <span class="float-end text-light" id="precioRegular">S/.
                {{ $ruta->precio_regular }}</span></h4>
        <hr>
        <h6>Precio Actual: <span class="float-end text-success" id="precioActual">S/.
                {{ $ruta->precio_actual }}</span></h6>
        <h6>Descuento: <span class="text-danger float-end" id="descuento">S/.
                {{ $ruta->descuento ?? 0 }}</span></h6>
        <hr>
        <div class="d-flex justify-content-between">
            <p><strong>{{ $ruta->nombre_ruta }}</strong></p>
            <p class="text-center m-0" id="fechaSeleccionada">Selecciona una fecha</p>
            <p id="cantidadPersonasTexto">1 Persona</p>
        </div>
        <hr>
        <h5>Total: <span class="float-end h4 text-light" id="totalPago">S/. 0</span></h5>
        <hr>
        <h5>Total a Pagar 50%: <span class="float-end h3 text-success" id="total50">S/. 0</span></h5>
    </div>
</div>
