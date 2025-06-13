<!-- Modal Show -->
<div class="modal fade" id="show{{ $guia->id_guia }}" tabindex="-1" aria-labelledby="showLabel{{ $guia->id_guia }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="showLabel{{ $guia->id_guia }}">Detalles de la Guía</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> {{ $guia->id_guia }}</p>
                <p><strong>Nombre:</strong> {{ $guia->nombre }}</p>
                <p><strong>Apellido:</strong> {{ $guia->apellido }}</p>
                <p><strong>Teléfono:</strong> {{ $guia->telefono }}</p>
                <p><strong>Email:</strong> {{ $guia->email }}</p>
                {{-- Puedes agregar más campos si tu modelo los tiene, como dirección, idiomas, etc. --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
