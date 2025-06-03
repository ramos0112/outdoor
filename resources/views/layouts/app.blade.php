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
                <img src="{{ asset('imagenes/autdoor2.png') }}" height="48" alt="Inicio">
            </a>

            <!--<a class="navbar-brand fw-bold" href="/#">OUTDOOR <span class="text-danger">EXPEDITIONS</span></a>  -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('rutas.tipo', ['tipo' => 'Aventura']) }}">Aventura</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('rutas.tipo', ['tipo' => 'Trekking']) }}">Trekking</a></li>
                    <li class="nav-item"><a class="nav-link" href="blog">Blog</a></li>

                    <!--<li class="nav-item"><a class="nav-link" href="#">Contacto</a></li>-->
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido de la página -->
    @yield('plantilla') <!-- Sección para insertar contenido específico de cada vista -->
    <!-- Menú de íconos  ['icon' => 'envelope', 'text' => 'Contacto', 'url' => '#'], -->
    <div class="menu-section hidden lg:flex mb-5">
        <div class="menu-item"><i class="fas fa-home"></i><a href="/">Home</a></div>
        <div class="menu-item"><i class="fas fa-road"></i><a class="nav-link"
                href="{{ route('rutas.tipo', ['tipo' => 'Aventura']) }}">Aventura</a></div>
        <div class="menu-item"><i class="fas fa-hiking"></i><a class="nav-link"
                href="{{ route('rutas.tipo', ['tipo' => 'Trekking']) }}">Trekking</a></div>
        <div class="menu-item"><i class="fas fa-book"></i><a href="/blog">Blog</a></div>
        <!--<div class="menu-item"><i class="fas fa-envelope"></i><a href="#">Contacto</a></div>-->
    </div>
    <!-- Pie de página -->
    <section class="packages">
        <div class="container">
            <footer class="footer mt-auto">
                <div>
                    <h1 class="text-3xl font-bold">Sobre nosotros</h1>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <h3 class="text-xl font-bold mt-4">¿Quiénes somos?</h3>
                            <p>Somos una agencia de viajes 𝗰𝗼𝗻𝗳𝗶𝗮𝗯𝗹𝗲 𝘆 𝗹𝗶𝗱𝗲𝗿𝗮𝗱𝗮 𝗽𝗼𝗿
                                𝗹𝗶𝗰𝗲𝗻𝗰𝗶𝗮𝗱𝗼𝘀 𝗲𝗻 𝗧𝘂𝗿𝗶𝘀𝗺𝗼 de la UNT. Pioneros en operar 𝗖𝗮𝗺𝗶𝗻𝗼
                                𝗜𝗻𝗰𝗮 Huaylillas, Rutas de Trekking y en aperturar corredores turísticos en toda la
                                región La Libertad 🇵🇪.</p>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-xl font-bold mt-4">Soporte</h3>
                            <ul class="list-unstyled">
                                <p><a href="https://n9.cl/1bkel">Términos y condiciones</a></p>
                                <p><a href="#">Políticas de privacidad</a></p>
                                <p><a href="https://reclamos.outdoorexpeditionspe.com/">Libro de reclamaciones</a></p>
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
                <p class="text-sm text-gray-600 mt-4">
                    &copy; Outdoor Expeditions. Todos los derechos reservados | Desarrollado por: outdoorexpeditions <a
                        href="https://github.com/" class="text-blue-500 hover:underline"></a>
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
    @yield('scripts') <!-- Permite agregar scripts específicos en las vistas -->
</body>
</html>
