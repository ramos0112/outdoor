<!-- Modal -->
<div class="modal fade" id="show{{ $ruta->id_ruta }}" tabindex="-1" aria-labelledby="showLabel{{ $ruta->id_ruta }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showLabel{{ $ruta->id_ruta }}">Detalle de Ruta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> {{ $ruta->id_ruta }}</p>
                <p><strong>Nombre:</strong> {{ $ruta->nombre_ruta }}</p>
                <p><strong>Descripción:</strong> {{ $ruta->descripcion_general }}</p>
                <p><strong>Tipo:</strong> {{ $ruta->tipo }}</p>
                <p><strong>Precio Regular:</strong> {{ $ruta->precio_regular }}</p>
                <p><strong>Descuento:</strong> {{ $ruta->descuento }}</p>
                <p><strong>Precio Actual:</strong> {{ $ruta->precio_actual }}</p>
                <p><strong>Dificultad:</strong> {{ $ruta->dificultad }}</p>
                <p><strong>Estado:</strong> {{ $ruta->estado }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
    