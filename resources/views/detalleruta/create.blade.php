<!-- resources/views/detalleruta/create.blade.php -->
<!-- Modal -->
<div class="modal fade" id="create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('detalleruta.store') }}" method="POST" id="detalle-ruta-form-create">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Crear Nuevo Detalle de Ruta</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="mb-3">
                        <label for="id_ruta" class="form-label">Ruta</label>
                        <select class="form-control" name="id_ruta" id="id_ruta" style="width: 100%" required >
                            <option value="">Seleccionar Ruta</option>
                            @foreach ($rutas as $ruta)
                                <option value="{{ $ruta->id_ruta }}">{{ $ruta->nombre_ruta }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" name="descripcion" id="descripcion" rows="3" required></textarea>
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
