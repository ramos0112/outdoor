<div class="modal fade" id="modalClientes" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title adventure-h2 text-white m-0" style="font-size: 1.2rem;">
                    <i class="fas fa-user-plus me-2 text-danger"></i>Añadir Acompañante
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4 bg-creme">
                <form id="formCliente">
                    <div class="custom-input-group">
                        <label>Tipo documento</label>
                        <select id="tipo_documento_modal" name="tipo_documento_modal" class="form-control-exp" required>
                            <option value="">Seleccionar</option>
                            <option value="dni">DNI</option>
                            <option value="pasaporte">Pasaporte</option>
                            <option value="otro">Otro</option>
                        </select>
                    </div>

                    <div class="custom-input-group">
                        <label>N° documento</label>
                        <input placeholder="Solo números" type="text" id="numero_documento_modal"
                            name="numero_documento_modal" maxlength="9" onblur="buscarDocumentoModal()"
                            class="form-control-exp" required>
                    </div>

                    <div id="spinnerModal" style="display: none;" class="text-center my-3">
                        <div class="spinner-border text-danger" role="status" style="width: 1.5rem; height: 1.5rem;"></div>
                        <p class="mt-2 small text-muted fw-bold">Consultando RENIEC...</p>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 custom-input-group">
                            <label>Nombres</label>
                            <input type="text" placeholder="Nombres" id="nombre_modal" name="nombre_modal" class="form-control-exp" required>
                        </div>
                        <div class="col-6 custom-input-group">
                            <label>Apellidos</label>
                            <input type="text" placeholder="Apellidos" id="apellido_modal" name="apellido_modal" class="form-control-exp" required>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6 custom-input-group">
                            <label>Fecha de Nac.</label>
                            <input type="date" class="form-control-exp" id="fecha_nacimiento_modal" name="fecha_nacimiento_modal" required>
                        </div>
                        <div class="col-6 custom-input-group">
                            <label>Teléfono</label>
                            <input type="text" placeholder="999 999 999" id="telefono_modal" name="telefono_modal" class="form-control-exp" required>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer bg-light border-0 p-3">
                <button class="btn btn-link text-muted text-decoration-none fw-bold" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-exp-primary px-4 py-2" onclick="agregarCliente()" style="min-width: 120px;">
                    <i class="fas fa-plus me-2"></i>AGREGAR
                </button>
            </div>
        </div>
    </div>
</div>