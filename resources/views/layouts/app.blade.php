<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title> <!-- Título de la página -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Enlace a Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Carga de FontAwesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Enlace al archivo de estilos personalizado -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <!-- Enlace a Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">


    <!-- Estilos personalizados -->
    @yield('head') <!-- Permitirá agregar contenido extra en las vistas que lo extienden -->
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a href="{{ url('/#') }}">
                <img src="{{ asset('imagenes/logo.png') }}" height="48" alt="Inicio">
            </a>

            <!--<a class="navbar-brand fw-bold" href="/#">OUTDOOR <span class="text-danger">EXPEDITIONS</span></a>  -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/#">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{ route('rutas.tipo', ['tipo' => 'Aventura']) }}">Full Day</a></li>
                    <li class="nav-item"><a class="nav-link"
                            href="{{ route('rutas.tipo', ['tipo' => 'Trekking']) }}">Trekking</a></li>
                    <li class="nav-item"><a class="nav-link" href="blog">Nosotros</a></li>

                    <!--<li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>-->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido de la página -->
    @yield('plantilla') <!-- Sección para insertar contenido específico de cada vista -->
    <!-- Menú de íconos  ['icon' => 'envelope', 'text' => 'Contacto', 'url' => '#'], -->
    <div class="menu-section hidden lg:flex mb-5">
        <div class="menu-item"><i class="fas fa-home"></i><a href="/">Inicio</a></div>
        <div class="menu-item"><i class="fas fa-road"></i><a class="nav-link"
                href="{{ route('rutas.tipo', ['tipo' => 'Aventura']) }}">Full Day</a></div>
        <div class="menu-item"><i class="fas fa-hiking"></i><a class="nav-link"
                href="{{ route('rutas.tipo', ['tipo' => 'Trekking']) }}">Trekking</a></div>
        <div class="menu-item"><i class="fas fa-book"></i><a href="/blog">Nosotros</a></div>
        <!--<div class="menu-item"><i class="fas fa-envelope"></i><a href="#">Contacto</a></div>-->
    </div>
    <!-- Pie de página -->
    <section class="packages">
        <div class="container">
            <footer class="footer mt-auto">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <!-- Logo -->
                            <div class="logo-container text-center">
                                <img src="{{ asset('imagenes/logo_animation.png') }}" alt="Outdoor Expeditions"
                                    class="img-fluid" style="max-width: 100px; height: auto;">
                                <img src="{{ asset('imagenes/Certificado.jpeg') }}" alt="Outdoor Expeditions"
                                    class="img-fluid" style="max-width: 70px; height: auto;">
                            </div>

                            <!-- Llamado a la acción -->
                            <div class="cta-container text-center mt-1">
                                <h3 class="text-xl font-bold">¡Explora nuestros Tours!</h3>
                                <p>Haz de tus aventuras un recuerdo inolvidable con Outdoor Expeditions.
                                </p>
                                <a href="https://wa.link/0037yw" target="_blank" class="btn btn-danger"> <i
                                        class="fab fa-whatsapp"></i> ¡Reserva
                                    ahora!</a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h3 class="text-xl font-bold mt-4">Soporte</h3>
                            <ul class="list-unstyled">
                                <p><a href="https://n9.cl/1bkel">Términos y condiciones</a></p>
                                <p><a href="#">Políticas de privacidad</a></p>
                                <p><a href="https://reclamos.outdoorexpeditionspe.com/">Libro de reclamaciones</a></p>
                                <p></p><a href="#">Codigó ESNNA</a></p>
                                <p></p>
                                </p><a href="#">Certificaciones</a></p>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-xl font-bold mt-4">Contáctanos</h3>

                            <p><i class="fas fa-phone"></i> +51 961358621</p>

                            <p>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:outdoorexpeditionsperu@gmail.com">outdoorexpeditionsperu@gmail.com</a>
                            </p>

                            <p>
                                <i class="fab fa-whatsapp"></i>
                                <a href="https://wa.link/0037yw" target="_blank">Escríbenos al WhatsApp</a>
                            </p>

                            <h6 class="text-xl font-bold mt-4">Síguenos en:</h6>

                            <div class="d-flex justify-content-center gap-4 fs-4">
                                <a href="https://www.facebook.com/profile.php?id=100091928552440&mibextid=ZbWKwL"
                                    target="_blank">
                                    <i class="fab fa-facebook text-primary"></i>
                                </a>
                                <a href="https://www.instagram.com/outdoorexpeditions.pe?igsh=bDA2aDI5cWFoZ2wy"
                                    target="_blank">
                                    <i class="fab fa-instagram text-danger"></i>
                                </a>
                                <a href="https://www.tiktok.com/@outdoorexpeditions?_t=ZS-8wbSI5Vbr6H&_r=1"
                                    target="_blank">
                                    <i class="fab fa-tiktok" style="color: white;"></i>
                                </a>
                                <a href="https://youtube.com/@outdoorexpeditions_pe?si=3E92hl_x5kjR1SVe"
                                    target="_blank">
                                    <i class="fab fa-youtube text-danger"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <p style="font-size: 10px;" class="text-gray-100 mt-4">
                    &copy; Outdoor Expeditions. Todos los derechos reservados | Desarrollado por: outdoorexpeditions
                    <a href="#" class="text-blue-500 hover:underline"></a>
                </p>

            </footer>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const packagesPerPage = 8; // Número de paquetes por página
        let currentPage = 1; // Página actual

        // Función para cargar los paquetes de una página
        function loadPage(pageNumber) {
            currentPage = pageNumber;

            const allPackages = document.querySelectorAll('.package');
            const start = (currentPage - 1) * packagesPerPage;
            const end = start + packagesPerPage;

            // Ocultar todos los paquetes
            allPackages.forEach(package => package.style.display = 'none');

            // Mostrar solo los paquetes correspondientes a la página seleccionada
            for (let i = start; i < end; i++) {
                if (allPackages[i]) {
                    allPackages[i].style.display = 'block';
                }
            }

            // Actualizar la barra de paginación
            updatePagination();
        }

        // Función para actualizar los botones de la barra de paginación
        function updatePagination() {
            const allPackages = document.querySelectorAll('.package');
            const totalPages = Math.ceil(allPackages.length / packagesPerPage); // Calculamos el número total de páginas
            const paginationContainer = document.querySelector('.pagination');

            // Limpiar la barra de paginación antes de agregar nuevos botones
            paginationContainer.innerHTML = '';

            // Crear los botones de la paginación dinámicamente
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.classList.add('page-btn');
                pageButton.textContent = i;
                pageButton.onclick = () => loadPage(i);

                // Desactivar el botón de la página actual
                if (i === currentPage) {
                    pageButton.disabled = true;
                    pageButton.classList.add('active');
                }

                paginationContainer.appendChild(pageButton);
            }
        }

        // Inicialización: cargar la primera página
        window.onload = function() {
            loadPage(1);
        };
    </script>
    @yield('scripts')


    {{--  cambios------------------------------------------------------- --}}
    <!-- Cuadro flotante con solo el ícono de WhatsApp -->
    <div class="whatsapp-chat" id="whatsappChat">
        <a href="javascript:void(0)" id="openModal">
            <img src="{{ asset('imagenes/whatsapp-logo.png') }}" alt="WhatsApp" class="whatsapp-logo">
        </a>
    </div>

    <!-- Modal (cuadro emergente) -->
    <div class="modal" id="whatsappModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header del modal con color verde -->
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title">Inicie una conversación</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="closeModal"></button>
                </div>
                <div class="modal-body">
                    <p>Estamos en línea las 24 horas, ¿Tienes alguna pregunta o necesitas más información sobre nuestros
                        tours?</p>
                    <div class="d-flex justify-content-center"> <!-- Esta clase centra solo el botón -->
                        <a href="https://wa.link/0037yw" target="_blank" class="btn btn-success">¡Chatea ahora!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Obtener el modal, los botones de abrir y cerrar
        const whatsappChatIcon = document.getElementById("openModal");
        const whatsappModal = document.getElementById("whatsappModal");
        const closeModalButton = document.getElementById("closeModal");

        // Función para alternar la visibilidad del modal
        whatsappChatIcon.onclick = function() {
            // Si el modal está visible, lo cerramos, de lo contrario, lo mostramos
            if (whatsappModal.style.display === "block") {
                whatsappModal.style.display = "none";
            } else {
                whatsappModal.style.display = "block";
            }
        }

        // Función para cerrar el modal al hacer clic en el botón de cerrar
        closeModalButton.onclick = function() {
            whatsappModal.style.display = "none";
        }

        // Cerrar el modal si el usuario hace clic fuera del área del modal
        window.onclick = function(event) {
            if (event.target === whatsappModal) {
                whatsappModal.style.display = "none";
            }
        }
    </script>


</body>

</html>
