    <!-- Modal para agregar acompañantes -->
    <div class="modal fade" id="modalClientes" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Agregar Acompañante</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar cliente -->
                    <form id="formCliente">
                        <label>Tipo documento</label>
                        <select id="tipo_documento_modal" name="tipo_documento_modal" class="form-select" required>
                            <option value="">Seleccionar</option>
                            <!--<option value="dni">DNI</option>-->
                            <option value="pasaporte">Pasaporte</option>
                            <option value="otro">Otro</option>
                        </select>

                        <label>N° documento</label>
                        <input placeholder="Solo números" type="text" id="numero_documento_modal"
                            name="numero_documento_modal" maxlength="9" onblur="buscarDocumentoModal()"
                            class="form-control mb-2" required>

                        <div id="spinnerModal" style="display: none;" class="text-center my-2">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Buscando...</span>
                            </div>
                            <p class="mt-1">Consultando RENIEC...</p>
                        </div>
                        <label>Nombres</label>
                        <input type="text" placeholder="Nombres" id="nombre_modal" name="nombre_modal"
                            class="form-control mb-2" required>

                        <label>Apellidos</label>
                        <input type="text" placeholder="Apellidos" id="apellido_modal" name="apellido_modal"
                            class="form-control mb-2"   required>

                        <label>Fecha de Nac.</label>
                        <input type="date" class="form-control mb-2" id="fecha_nacimiento_modal"name="fecha_nacimiento_modal" required>

                        <label>telefono</label>
                        <input type="text" placeholder="telefono" id="telefono_modal" name="telefono_modal"
                            class="form-control mb-2" required>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" onclick="agregarCliente()">Agregar</button>
                </div>
            </div>
        </div>
    </div>
