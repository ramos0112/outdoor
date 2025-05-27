    <!--resurces/views/listareservas/modalClientes.blade.php-->
    <!-- Modal para agregar acompañantes -->
    <div class="modal fade" id="modalClientes" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content ">
                <div class="modal-header bg-success">
                    <h5 class="modal-title">Agregar Acompañante</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar cliente -->
                    <form id="formCliente">
                        <label>Tipo documento</label>
                        <select id="tipo_documento_modal" name="tipo_documento_modal" class="form-select">
                            <option value="">Seleccionar</option>
                            <option value="dni">DNI</option>
                            <option value="pasaporte">Pasaporte</option>
                            <option value="otro">Otro</option>
                        </select>

                        <label>N° documento</label>
                        <input placeholder="Solo números" type="text" id="numero_documento_modal"
                            name="numero_documento_modal" maxlength="9"
                            class="form-control mb-2">

                        <label>Nombres</label>
                        <input type="text" placeholder="Nombres" id="nombre_modal" name="nombre_modal"
                            class="form-control mb-2">

                        <label>Apellidos</label>
                        <input type="text" placeholder="Apellidos" id="apellido_modal" name="apellido_modal"
                            class="form-control mb-2">

                        <label>Fecha de Nac.</label>
                        <input type="date" class="form-control mb-2"
                            id="fecha_nacimiento_modal"name="fecha_nacimiento" required>

                        <label>Email</label>
                        <input type="email" placeholder="Email" id="email_modal" name="email_modal"
                            class="form-control mb-2">
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" onclick="agregarCliente()">Agregar</button>
                </div>
            </div>
        </div>
    </div>
