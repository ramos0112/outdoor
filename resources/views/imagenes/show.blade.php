        <!-- Modal para Ver Imagen -->
        <div class="modal fade" id="show{{ $imagen->id_imagen }}" data-bs-backdrop="static" data-bs-keyboard="false"
            tabindex="-1" aria-labelledby="showModalLabel{{ $imagen->id_imagen }}" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="showModalLabel{{ $imagen->id_imagen }}">Detalles de la Imagen</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <strong>Ruta:</strong> {{ $imagen->ruta->nombre_ruta }}
                        </div>
                        <div class="mb-3">
                            <strong>Imagen:</strong><br>
                            <img src="{{ asset($imagen->url_imagen) }}" alt="Imagen de Ruta" class="img-fluid">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
