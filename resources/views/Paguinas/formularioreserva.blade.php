<!-- resources/views/paguinas/formularioreserva.blade.php -->
@extends('layouts.app')

@section('title', 'Formulario de Reserva')

@section('plantilla')

    <section class="hero text-white text-center py-4">
        
        <h1 class="text-3xl font-bold sm:text-2xl md:text-4xl">Reserva con "Outdoor <span
                class="text-danger">Expeditions"</span></h1>
        <h1>{{ $ruta->nombre_ruta ?? 'Ruta desconocida' }} te espera</h1>
    </section>

    <section class="container-fluid py-5 bg-dark">
        <div class="row justify-content-center text-white g-0">
            <h2 class="display-5 fw-bold mb-4 text-center">Estás a solo un paso de vivir una experiencia inolvidable</h2>

            <div class="col-md-5 p-4 rounded border border-light bg-secondary bg-opacity-25 me-md-5">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h5 class="fw-bold mb-4 text-center">Datos de cliente y reserva</h5>
                <form action="{{ route('mercadopago.checkout') }}" method="POST" id="formReserva">
                    @csrf

                    <!-- Campo oculto para el ID de la ruta -->
                    <input type="hidden" id="id_ruta" name="id_ruta" value="{{ $ruta->id_ruta }}">
                    <input type="hidden" id="monbre_ruta" name="monbre_ruta" value="{{ $ruta->nombre_ruta }}">
                    <input type="hidden" id="precio_actual" name="precio_actual" value="{{ $ruta->precio_actual }}">
                    <input type="hidden" id="precio_regular" name="precio_regular" value="{{ $ruta->precio_regular }}">
                    <input type="hidden" id="metodo_pago" name="metodo_pago" value="web">
                    <input type="hidden" id="monto_pagado" name="monto_pagado" value="0">
                    

                    <!-- Documento y nombres -->
                    <div class="row mb-3">
                        <div class="col">
                            <label>Tipo documento</label>
                            <select class="form-select" name="tipo_documento" id="tipo_documento" required>
                                <option value="">Seleccionar</option>
                               <option value="dni">DNI</option>
                                <option value="pasaporte">Pasaporte</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="col">
                            <!--DESCOMENTAR AQUI PARA CONSULTAR POR DNI-->
                            <label for="numero_documento" class="form-label">N° Documento</label>
                            <input type="text" class="form-control" name="numero_documento" id="numero_documento"
                                maxlength="9" onblur="buscarDocumentoPrincipal()" required>

                            <div id="spinnerPrincipal" style="display: none;" class="text-center my-2">
                                <div class="spinner-border text-info" role="status">
                                    <span class="visually-hidden">Buscando...</span>
                                </div>
                                <p class="mt-1">Consultando RENIEC...</p>
                            </div>
                        </div>
                    </div>
                    <!-- Campos de nombres, apellidos y fecha de nacimiento-->
                    <input type="text" class="form-control mb-2" placeholder="Nombres" name="nombre" id="nombre"
                        required>
                    <input type="text" class="form-control mb-2" placeholder="Apellidos" name="apellido" id="apellido"
                        required>
                    <input placeholder="Fecha de nacimiento" type="date" class="form-control mb-2" id="fecha_nacimiento"  name="fecha_nacimiento" required>

                    <!-- Campos de dirección -->
                    <div class="row mb-2">
                        <div class="col"><input type="text" class="form-control" placeholder="País" name="pais" id="pais" required/></div>
                        <div class="col"><input type="text" class="form-control" placeholder="Región" name="region" id="region" required></div>
                        <div class="col"><input type="text" class="form-control" placeholder="Ciudad" name="ciudad" id="ciudad" required></div>
                    </div>

                    <!-- Campos de contacto -->
                    <input type="text" class="form-control mb-2" placeholder="Teléfono" name="telefono" id="telefono" required>
                    <input type="email" class="form-control mb-2" placeholder="Email" name="email" id="email" required>

                    <!-- Selección de fecha (filtrada para mostrar solo fechas futuras) -->
                    @php
                        $hoy = \Carbon\Carbon::today(); // Obtener la fecha de hoy
                    @endphp
                    <div class="mb-3">
                        <select class="form-control" id="id_fecha" name="id_fecha">
                            <option>Seleccionar fecha</option>
                            @foreach ($ruta->fechasDisponibles as $fecha)
                                @if (\Carbon\Carbon::parse($fecha->fecha)->gte($hoy))
                                    <!-- Filtrar fechas pasadas -->
                                    <option value="{{ $fecha->id_fecha }}">
                                        {{ \Carbon\Carbon::parse($fecha->fecha)->format('d/m/Y') }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <!-- Campo oculto para almacenar los acompañantes pasarlos al controlador como query  y gaudarlos en la tabla cliente -->
                    <input type="hidden" name="acompanantes" id="acompanantesInput">

                    <!-- Selección de cantidad de personas -->
                    <div class="row align-items-end mb-4 ">
                        <div class="col-8">
                            <label for="cantidad_personas">Cantidad de personas</label>
                            <input type="number" class="form-control" id="cantidad_personas" value="1"
                                name="cantidad_personas">
                        </div>
                        <div class="col-4 d-grid">
                            <!-- Botón para abrir el modal de agregar acompañantes -->
                            <button type="button" class="btn btn-primary mt-4" data-bs-toggle="modal"
                                data-bs-target="#modalClientes">Agregar</button>
                        </div>
                    </div>
                    <!-- Contenedor con scroll si hay más de 4 filas -->
                    <div class="bg-black p-3 rounded border border-secondary mb-5"
                        style="max-height: 200px; overflow-y: auto;">
                        <table class="table table-bordered table-dark table-sm mt-3" id="tablaClientes">
                            <thead>
                                <tr>
                                    <th class="text-center">N° Documento</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Fecha Nac.</th>
                                    <th class="text-center">Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Filas se agregarán dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold" id="btnPagar">RESERVAR S/.
                        00.00</button>
                </form>
            </div>
            @include('Paguinas.resumenruta')
        </div>
    </section>

    @include('Paguinas.modalclientes')
 <script src="https://sdk.mercadopago.com/js/v2"></script>
    <!-- Estilos para el calendario  flatpickr fecha de nacimiento -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Script para funcionalidad -->
    <script>
        const tabla = document.querySelector('#tablaClientes tbody');
        const cantidadInput = document.getElementById('cantidad_personas');
        const cantidadTexto = document.getElementById('cantidadPersonasTexto');
        const fechaSeleccionada = document.getElementById('fechaSeleccionada');
        const totalPago = document.getElementById('totalPago');
        const total50 = document.getElementById('total50');
        const modalClientes = document.getElementById('modalClientes'); // Modal corregido
        let Clientes = [];

        // Actualizar fecha seleccionada
        document.getElementById('id_fecha').addEventListener('change', function() {
            const selectedFecha = this.options[this.selectedIndex].text;
            fechaSeleccionada.textContent = selectedFecha;
            actualizarTotal();
        });
        // Función para agregar un acompañante
        function agregarCliente() {
            const tipo_documento = document.getElementById('tipo_documento_modal').value;
            const numero_documento = document.getElementById('numero_documento_modal').value;
            const nombre = document.getElementById('nombre_modal').value;
            const apellido = document.getElementById('apellido_modal').value;
            const fecha_nacimiento = document.getElementById('fecha_nacimiento_modal').value;
            const telefono = document.getElementById('telefono_modal').value;
           // const email = document.getElementById('email_modal').value;

            // Verificación de que todos los campos estén llenos
            if (!numero_documento || !nombre || !apellido || !fecha_nacimiento || !telefono) {
                alert('Completa todos los campos');
                return;
            }
            // Verificar si el número de documento ya existe en el arreglo
            const documentoExistente = Clientes.some(cliente => cliente.doc === numero_documento);
            if (documentoExistente) {
                alert('Este número de documento ya está registrado.');
                return;
            }

            // Añadir el cliente al arreglo
            Clientes.push({
                tipo: tipo_documento,
                doc: numero_documento,
                nombre: nombre,
                apellido: apellido,
                fecha: fecha_nacimiento,
                telefono: telefono
            });

            // Actualizar la tabla
            actualizarTabla();

            // Actualizar la cantidad de personas
            cantidadInput.value = 1 + Clientes.length;
            cantidadTexto.textContent = `${cantidadInput.value} Personas`;

            // Resetear el formulario y cerrar el modal
            document.getElementById('formCliente').reset();
            const modalInstance = bootstrap.Modal.getInstance(modalClientes); // Obtener la instancia del modal
            modalInstance.hide(); // Cerrar el modal
            actualizarTotal();
            document.getElementById('acompanantesInput').value = JSON.stringify(Clientes);

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
                    <td class="text-center"><button class="btn btn-danger btn-sm" onclick="eliminarAcompanante(${index})"><i class="fas fa-trash"></i></button></td>
                </tr>`;
                tabla.innerHTML += fila;
            });
        }
        // Función para eliminar un acompañante
        function eliminarAcompanante(index) {
            Clientes.splice(index, 1); // Eliminar el acompañante
            actualizarTabla();
            cantidadInput.value = 1 + Clientes.length; // Actualizar la cantidad de personas
            actualizarTotal();
        }
        // Actualizar total
        function actualizarTotal() {
            const cantidad_personas = 1 + Clientes.length;
            const precioActual = parseFloat("{{ $ruta->precio_actual }}"); // Precio actual
            const total = cantidad_personas * precioActual;
            totalPago.textContent = `S/. ${total.toFixed(2)}`;
            total50.textContent = `S/. ${(total / 2).toFixed(2)}`;
            document.getElementById('btnPagar').textContent = `Pagar S/.${(total / 2).toFixed(2)}`;

            // Aquí asignamos el valor de total50 al campo monto_pagado
            document.getElementById('monto_pagado').value = (total / 2).toFixed(2); // Asignamos el valor de total50
        }
        flatpickr("#fecha_nacimiento", {
            locale: "es", // Configura el idioma en español
            dateFormat: "Y-m-d", // Formato de la fecha (Año-Mes-Día)
            minDate: "1900-01-01", // Fecha mínima
            maxDate: "2100-12-31" // Fecha máxima
        });
    </script>

    <script>
        const TOKEN_RENIEC =
            'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Impob25hdGFucmFtb3M5NDRAZ21haWwuY29tIn0.9ceS54wbw3njhTVXKAwrZl0rNhsZxE5RyiDOIykvnaY'; // token real

        function buscarPorDniPrincipal() {
            const dni = document.getElementById('numero_documento').value;
            const spinner = document.getElementById('spinnerPrincipal');
            spinner.style.display = 'block'; // Mostrar

            if (dni.length === 8) {
                fetch(
                        `https://dniruc.apisperu.com/api/v1/dni/${dni}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Impob25hdGFucmFtb3M5NDRAZ21haWwuY29tIn0.9ceS54wbw3njhTVXKAwrZl0rNhsZxE5RyiDOIykvnaY`
                    )
                    .then(res => res.json())
                    .then(data => {
                        if (data.success !== false && data.nombres) {
                            document.getElementById('nombre').value = data.nombres;
                            document.getElementById('apellido').value =
                                `${data.apellidoPaterno} ${data.apellidoMaterno}`;
                        } else {
                            alert("DNI no encontrado.");
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert("Hubo un error al consultar la API.");
                    })
                    .finally(() => {
                        spinner.style.display = 'none'; // Ocultar spinner
                    });
            } else {
                alert("Ingrese un DNI válido.");
                spinner.style.display = 'none';
            }
        }

        function buscarPorDniModal() {
            const dni = document.getElementById('numero_documento_modal').value;
            const spinner = document.getElementById('spinnerModal');
            spinner.style.display = 'block'; // Mostrar spinner

            if (dni.length === 8) {
                fetch(
                        `https://dniruc.apisperu.com/api/v1/dni/${dni}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6Impob25hdGFucmFtb3M5NDRAZ21haWwuY29tIn0.9ceS54wbw3njhTVXKAwrZl0rNhsZxE5RyiDOIykvnaY`
                    )
                    .then(res => res.json())
                    .then(data => {
                        if (data.success !== false && data.nombres) {
                            document.getElementById('nombre_modal').value = data.nombres;
                            document.getElementById('apellido_modal').value =
                                `${data.apellidoPaterno} ${data.apellidoMaterno}`;
                        } else {
                            alert("DNI no encontrado.");
                        }
                    })
                    .catch(err => {
                        console.error('Error:', err);
                        alert("Hubo un error al consultar la API.");
                    })
                    .finally(() => {
                        spinner.style.display = 'none'; // Ocultar spinner
                    });
            } else {
                alert("Ingrese un DNI válido (8 dígitos).");
                spinner.style.display = 'none'; // Ocultar spinner si es inválido
            }
        }

        function buscarDocumentoPrincipal() {
            const tipo = document.getElementById('tipo_documento').value;
            const numero = document.getElementById('numero_documento').value;

            if (tipo === 'dni') {
                buscarPorDniPrincipal(numero);
            } else {
                // Si es otro tipo, simplemente no hace nada o puedes limpiar los campos:
                document.getElementById('nombre').value = '';
                document.getElementById('apellido').value = '';
            }
        }


        function buscarDocumentoModal() {
            const tipo = document.getElementById('tipo_documento_modal').value;
            const numero = document.getElementById('numero_documento_modal').value;

            if (tipo === 'dni') {
                buscarPorDniModal(numero);
            } else {
                // Si es otro tipo, simplemente no hace nada o puedes limpiar los campos:
                document.getElementById('nombre_modal').value = '';
                document.getElementById('apellido_modal').value = '';
            }
        }
    </script>



@endsection
