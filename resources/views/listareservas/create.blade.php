<!-- Modal Crear Reserva -->
<div class="modal fade" id="modalCreate" tabindex="-1" data-bs-backdrop="static" aria-labelledby="modalCreateLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="formReserva" method="POST" action="{{ route('listareservas.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Agregar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <!-- RUTA y FECHA -->
                        <div class="col-md-6">
                            <label for="id_ruta" class="form-label">Ruta</label>
                            <select name="id_ruta" id="id_ruta" class="form-select select2" style="width: 100%"
                                required>
                                <option value="">Seleccione una ruta</option>
                                @foreach ($rutas as $ruta)
                                    <option value="{{ $ruta->id_ruta }}">{{ $ruta->nombre_ruta }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="id_fecha" class="form-label">Fecha disponible</label>
                            <select name="id_fecha" id="id_fecha" class="form-select" required>
                                <option value="">Seleccione una fecha</option>
                            </select>
                        </div>

                        <!-- CLIENTE -->
                        <div class="col-md-4">
                            <label class="form-label">Tipo Documento</label>
                            <select id="tipo_documento" name="tipo_documento" class="form-select" required>
                                <option value="">Seleccionar</option>
                                <option value="DNI">DNI</option>
                                <option value="Pasaporte">Pasaporte</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">N° Documento</label>
                            <input type="text" id="numero_documento" name="numero_documento" class="form-control"
                                required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Fecha Nacimiento</label>
                            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Nombre</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido</label>
                            <input type="text" id="apellido" name="apellido" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teléfono</label>
                            <input type="text" id="telefono" name="telefono" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">País</label>
                            <input type="text" id="pais" name="pais" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Región</label>
                            <input type="text" id="region" name="region" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" id="ciudad" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label for="id_movilidad" class="form-label">Asignar Movilidad</label>
                            <select name="id_movilidad" id="id_movilidad" class="form-select select2"
                                style="width: 100%" required>
                                <option value="">Seleccione una Movilidad</option>
                                @foreach ($movilidades as $movilidad)
                                    <option value="{{ $movilidad->id_movilidad }}">-Ruta: {{ $movilidad->ruta }} -Cap: {{ $movilidad->capacidad }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Aquí empieza la distribución de las dos columnas -->
                        <div class="row">
                            <!-- Columna 1: Reserva y Pago -->
                            <div class="col-md-6">
                                <div class="mb-4 row">
                                    <div class="col-8">
                                        <label for="cantidad_personas" class="form-label">Cantidad de personas</label>
                                        <input type="number" class="form-control" id="cantidad_personas"
                                            value="1" min="1" max="10" name="cantidad_personas"
                                            readonly>
                                    </div>
                                    <div class="col-4 d-grid">
                                        <!-- Botón para abrir el modal de agregar acompañantes -->
                                        <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal"
                                            data-bs-target="#modalClientes">Agregar</button>
                                    </div>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Método de Pago</label>
                                    <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                                        <option value="Efectivo">Efectivo</option>
                                        <option value="Yape">Yape</option>
                                        <option value="Plin">Plin</option>
                                        <option value="Transferencia">Transferencia</option>
                                        <option value="Otro">Otro</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Monto Pagado</label>
                                    <input type="number" step="0.01" id="monto_pagado" name="monto_pagado"
                                        class="form-control" required>
                                </div>
                            </div>

                            <!-- Columna 2: Tabla de Clientes -->
                            <div class="col-md-6">
                                <div class="col-md-12" style="max-height: 200px; overflow-y: auto;">
                                    <table class="table table-dark table-striped table-sm mt-3" id="tablaClientes">
                                        <thead>
                                            <tr>
                                                <th class="text-center">N° Documento</th>
                                                <th>Nombres</th>
                                                <th>Apellidos</th>
                                                <th>Fecha Nac.</th>
                                                <th class="text-center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Filas se agregarán dinámicamente -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!--input para almacenar los acompañantes-->
                        <input type="hidden" name="acompanantes" id="acompanantes">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Guardar Reserva</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </form>
    </div>
</div>
@include('listareservas.modalClientes')
<script>
    const tabla = document.querySelector('#tablaClientes tbody');
    const cantidadInput = document.getElementById('cantidad_personas');
    const Clientes = []; // Arreglo donde se almacenan los acompañantes

    // Función para agregar un acompañante
    function agregarCliente() {
        const tipo_documento = document.getElementById('tipo_documento_modal').value;
        const numero_documento = document.getElementById('numero_documento_modal').value;
        const nombre = document.getElementById('nombre_modal').value;
        const apellido = document.getElementById('apellido_modal').value;
        const fecha_nacimiento = document.getElementById('fecha_nacimiento_modal').value;
        const email = document.getElementById('email_modal').value;

        // Verificación de que todos los campos estén llenos
        if (!numero_documento || !nombre || !apellido || !fecha_nacimiento) {
            alert('Completa todos los campos');
            return;
        }
        // Añadir el cliente al arreglo
        Clientes.push({
            tipo: tipo_documento,
            doc: numero_documento,
            nombre: nombre,
            apellido: apellido,
            fecha: fecha_nacimiento,
            email: email
        });

        // Actualizar la tabla
        actualizarTabla();

        // Actualizar la cantidad de personas
        cantidadInput.value = 1 + Clientes.length;

        // Resetear el formulario y cerrar el modal
        document.getElementById('formCliente').reset();
        const modalInstance = bootstrap.Modal.getInstance(document.getElementById('modalClientes'));
        modalInstance.hide();
    }

    // Función para actualizar la tabla
    function actualizarTabla() {
        tabla.innerHTML = ''; // Limpiar la tabla
        Clientes.forEach((acomp, index) => {
            const fila = `
                <tr>
                    <td class="text-center">${acomp.doc}</td>
                    <td class="text-center">${acomp.nombre}</td>
                    <td class="text-center">${acomp.apellido}</td>
                    <td class="text-center">${acomp.fecha}</td>
                    <td class="text-center">
                        <button class="btn btn-danger btn-sm" onclick="eliminarAcompanante(${index})"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>`;
            tabla.innerHTML += fila;
        });
    }

    // Función para eliminar un acompañante
    function eliminarAcompanante(index) {
        Clientes.splice(index, 1); // Eliminar el acompañante
        actualizarTabla();
        cantidadInput.value = 1 + Clientes.length; // Actualizar la cantidad de personas
    }
    // Al enviar el formulario, convertir Clientes[] a JSON
    document.getElementById('formReserva').addEventListener('submit', function(e) {
        document.getElementById('acompanantes').value = JSON.stringify(Clientes);
    });
</script>
