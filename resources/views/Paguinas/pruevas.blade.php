<!-- resources/views/paguinas/formularioreserva.blade.php -->
@extends('layouts.app') <!-- Extiende la plantilla base de Laravel -->

@section('title', 'Formulario de Reserva') <!-- Define el título de la página -->

@section('plantilla') <!-- Inicio de la sección de contenido principal -->

    <!-- Sección Hero: Título principal y mensaje de bienvenida -->
    <section class="hero text-white text-center py-4">
        <x-logo /> <!-- Componente de logo, seguramente cargado desde una vista parcial -->
        <h1 class="text-3xl font-bold sm:text-2xl md:text-4xl">Reserva con "Outdoor <span
                class="text-danger">Expeditions"</span></h1>
        <p>{{ $ruta->nombre_ruta ?? 'Ruta desconocida' }} te espera</p> <!-- Muestra el nombre de la ruta o un mensaje por defecto -->
    </section>

    <!-- Sección principal del formulario de reserva -->
    <section class="container-fluid py-5 bg-dark">
        <div class="row justify-content-center text-white g-0">
            <h2 class="display-5 fw-bold mb-4 text-center">Estás a solo un paso de vivir una experiencia inolvidable</h2>

            <!-- Contenedor Izquierdo: Datos de facturación -->
            <div class="col-md-5 p-4 rounded border border-light bg-secondary bg-opacity-25 me-md-5">
                <h5 class="fw-bold mb-4 text-center">Datos de facturación</h5>
                <!-- Formulario de reserva -->
                <form action="{{ route('reservas.store') }}" method="POST" id="formReserva">
                    @csrf <!-- Protección contra ataques CSRF -->

                    <!-- Campo oculto para el ID de la ruta -->
                    <input type="hidden" name="ruta_id" value="{{ $ruta->id }}">

                    <!-- Campos de tipo de documento y número de documento -->
                    <div class="row mb-3">
                        <div class="col">
                            <label>Tipo documento</label>
                            <select class="form-select" name="tipo_documento" id="tipo_documento">
                                <option value="DNI">DNI</option>
                                <option value="CE">CE</option>
                            </select>
                        </div>
                        <div class="col">
                            <label for="numero_documento" class="form-label">N° Documento</label>
                            <input type="text" class="form-control" name="numero_documento" id="numero_documento"
                                required>
                        </div>
                    </div>

                    <!-- Campos de nombres, apellidos y fecha de nacimiento -->
                    <input type="text" class="form-control mb-2" placeholder="Nombres" name="nombre" id="nombre">
                    <input type="text" class="form-control mb-2" placeholder="Apellidos" name="apellido" id="apellido">
                    <input type="date" class="form-control mb-2" name="fecha_nacimiento">

                    <!-- Campos de dirección -->
                    <div class="row mb-2">
                        <div class="col"><input type="text" class="form-control" placeholder="País" name="pais"
                                id="pais"></div>
                        <div class="col"><input type="text" class="form-control" placeholder="Región" name="region"
                                id="region"></div>
                        <div class="col"><input type="text" class="form-control" placeholder="Ciudad" name="ciudad"
                                id="ciudad"></div>
                    </div>

                    <!-- Campos de contacto -->
                    <input type="text" class="form-control mb-2" placeholder="Teléfono" name="telefono" id="telefono">
                    <input type="email" class="form-control mb-2" placeholder="Email" name="email" id="email">

                    <!-- Selección de fecha (filtrada para mostrar solo fechas futuras) -->
                    @php
                        $hoy = \Carbon\Carbon::today(); // Obtener la fecha de hoy
                    @endphp
                    <div class="mb-3">
                        <select class="form-control" id="fecha_ruta" name="id_fecha">
                            <option>Seleccionar fecha</option>
                            @foreach ($ruta->fechasDisponibles as $fecha)
                                @if (\Carbon\Carbon::parse($fecha->fecha)->gte($hoy)) <!-- Filtrar fechas pasadas -->
                                    <option value="{{ $fecha->id }}">
                                        {{ \Carbon\Carbon::parse($fecha->fecha)->format('d/m/Y') }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div> 

                    <!-- Selección de cantidad de personas -->
                    <div class="row align-items-end">
                        <div class="col-8">
                            <label for="cantidad_personas">Cantidad de personas</label>
                            <input type="number" class="form-control" id="cantidad_personas" value="1" min="1"
                                max="10" name="cantidad_personas" readonly disabled>
                        </div>
                        <div class="col-4 d-grid">
                            <!-- Botón para abrir el modal de agregar acompañantes -->
                            <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal"
                                data-bs-target="#modalClientes">Agregar</button>
                        </div>
                    </div>

                    <!-- Tabla que mostrará los acompañantes -->
                    <table class="table table-bordered table-dark table-sm mt-3" id="tablaClientes">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>Fec. Nac.</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>

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

                <!-- Métodos de pago -->
                <h4 class="fw-bold text-center mb-3 text-light">Métodos de pago</h4>
                <div class="bg-black p-3 rounded border border-secondary">
                    <div class="mb-2">
                        <label for="tarjeta">Número de tarjeta</label>
                        <input type="text" id="tarjeta" class="form-control" name="tarjeta">
                    </div>

                    <div class="mb-2">
                        <label for="titular">Nombre del titular</label>
                        <input type="text" id="titular" class="form-control" name="titular">
                    </div>

                    <div class="row mb-2">
                        <div class="col">
                            <label for="vencimiento">Vencimiento</label>
                            <input type="text" id="vencimiento" class="form-control" name="vencimiento">
                        </div>
                        <div class="col">
                            <label for="codigoSeg">Código de Seg</label>
                            <input type="text" id="codigoSeg" class="form-control" name="codigo_seg">
                        </div>
                    </div>

                    <a href="https://www.google.com" class="text-center mt-2" target="_blank">Términos y condiciones</a>

                    <hr />
                    <button class="btn btn-success w-100 fw-bold" id="btnPagar">Pagar S/. 120</button>
                </div>
            </div>
        </div>
    </section>

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
                        <select id="tipo_documento_modal" class="form-control" name="tipo_documento" required>
                            <option>Seleccionar</option>
                            <option>DNI</option>
                            <option>Pasaporte</option>
                        </select>
                        <label>N° documento</label>
                        <input type="text" class="form-control mb-2" placeholder="Solo números"
                            id="numero_documento_modal" name="numero_documento" required>
                        <label>Nombres</label>
                        <input type="text" class="form-control mb-2" placeholder="Nombres" id="nombre_modal"
                            name="nombre" required>
                        <label>Apellidos</label>
                        <input type="text" class="form-control mb-2" placeholder="Apellidos" id="apellido_modal"
                            name="apellido" required>
                        <label>Fecha de Nac.</label>
                        <input type="date" class="form-control mb-2" id="fecha_nacimiento_modal"
                            name="fecha_nacimiento" required>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" onclick="agregarCliente()">Agregar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts para manejar la lógica de la reserva -->
    <script>
        const tabla = document.querySelector('#tablaClientes tbody');
        const cantidadInput = document.getElementById('cantidad_personas');
        const cantidadTexto = document.getElementById('cantidadPersonasTexto');
        const fechaSeleccionada = document.getElementById('fechaSeleccionada');
        const totalPago = document.getElementById('totalPago');
        const total50 = document.getElementById('total50');
        const modalCliente = document.getElementById('modalClientes');

        let Clientes = []; // Arreglo para almacenar los acompañantes

        // Escucha el cambio de la fecha seleccionada
        document.getElementById('fecha_ruta').addEventListener('change', function() {
            const selectedFecha = this.options[this.selectedIndex].text;
            fechaSeleccionada.textContent = selectedFecha; // Muestra la fecha seleccionada
            actualizarTotal(); // Actualiza el total
        });

        // Función para agregar un cliente desde el modal
        function agregarCliente() {
            const numero_documento = document.getElementById('numero_documento_modal').value;
            const nombre = document.getElementById('nombre_modal').value;
            const apellido = document.getElementById('apellido_modal').value;
            const fecha_nacimiento = document.getElementById('fecha_nacimiento_modal').value;

            // Verificación de que todos los campos estén llenos
            if (!numero_documento || !nombre || !apellido || !fecha_nacimiento) {
                alert('Completa todos los campos');
                return;
            }

            // Añadir el cliente al arreglo
            Clientes.push({
                doc: numero_documento,
                nombre: nombre,
                apellido: apellido,
                fecha: fecha_nacimiento
            });

            // Actualizar la tabla
            actualizarTabla();

            // Actualizar la cantidad de personas
            cantidadInput.value = 1 + Clientes.length;
            cantidadTexto.textContent = `${cantidadInput.value} Personas`;

            // Limpiar el formulario y cerrar el modal
            document.getElementById('formCliente').reset();
            const modal = new bootstrap.Modal(modalCliente);
            modal.hide();

            // Actualizar el total de la reserva
            actualizarTotal();
        }

        // Función para actualizar la tabla de clientes
        function actualizarTabla() {
            tabla.innerHTML = ''; // Limpiar la tabla antes de actualizarla
            Clientes.forEach((acomp, index) => {
                const fila = `
                    <tr>
                        <td>${acomp.doc}</td>
                        <td>${acomp.nombre}</td>
                        <td>${acomp.apellido}</td>
                        <td>${acomp.fecha}</td>
                        <td><button class="btn btn-danger btn-sm" onclick="eliminarCliente(${index})"><i class="fas fa-trash"></i></button></td>
                    </tr>`;
                tabla.innerHTML += fila; // Agregar la fila a la tabla
            });
        }

        // Función para eliminar un cliente de la lista
        function eliminarCliente(index) {
            Clientes.splice(index, 1);
            actualizarTabla();
            cantidadInput.value = 1 + Clientes.length;
            cantidadTexto.textContent = `${cantidadInput.value} Personas`;
            actualizarTotal();
        }

        // Función para actualizar el total de la reserva
        function actualizarTotal() {
            const cantidad_personas = 1 + Clientes.length; // 1 + porque ya se ha inicializado con 1 persona
            const precioActual = parseFloat("{{ $ruta->precio_actual }}"); // Precio actual de la ruta
            const total = cantidad_personas * precioActual;
            totalPago.textContent = `S/. ${total.toFixed(2)}`;
            total50.textContent = `S/. ${(total / 2).toFixed(2)}`;
            document.getElementById('btnPagar').textContent = `Pagar S/.${(total / 2).toFixed(2)}`;

        }
    </script>

@endsection
