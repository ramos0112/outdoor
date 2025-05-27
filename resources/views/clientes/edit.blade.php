<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body row g-3">
                    <div class="col-md-6">
                        <label>Nombre</label>
                        <input type="text" name="nombre" id="editNombre" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Apellido</label>
                        <input type="text" name="apellido" id="editApellido" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Tipo Documento</label>
                        <input type="text" name="tipo_documento" id="editTipoDocumento" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>N° Documento</label>
                        <input type="text" name="numero_documento" id="editNumeroDocumento" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Fecha Nacimiento</label>
                        <input type="date" name="fecha_nacimiento" id="editFechaNacimiento" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label>Teléfono</label>
                        <input type="text" name="telefono" id="editTelefono" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>País</label>
                        <input type="text" name="pais" id="editPais" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Región</label>
                        <input type="text" name="region" id="editRegion" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label>Ciudad</label>
                        <input type="text" name="ciudad" id="editCiudad" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-warning" type="submit">Actualizar</button>
                </div>
            </div>
        </form>
    </div>
</div>
