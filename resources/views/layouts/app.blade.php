<!-- resources/views/layouts/app.blade.php -->
@php
    $branding = \App\Models\Configuracion::obtener();
    // Valores por defecto para SEO
    $defaultTitle = $branding->nombre_empresa ?? 'Ayniforest | Agencia de Viajes y Turismo en Trujillo, Perú';
    $defaultDescription = $branding->meta_descripcion ?? 'Tours y Full Days en La Libertad, Trujillo. Descubre experiencias turísticas auténticas con Ayniforest, agencia especializada en aventura y cultura.';
    $defaultImage = $branding->og_image_url ?? asset('imagenes/og-image.jpg');
    $canonicalUrl = url()->current();
@endphp

<!DOCTYPE html>
<html lang="es">
 
<head>
    <!-- Meta etiquetas básicas -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    
    <!-- Título y descripción dinámicos -->
    <title>@yield('title', $defaultTitle)</title>
    <meta name="description" content="@yield('meta_description', $defaultDescription)">
    <meta name="keywords" content="@yield('meta_keywords', $branding->meta_keywords ?? 'tours, viajes, agencia de viajes, Trujillo, La Libertad, aventura')">
    
    <!-- Open Graph - Redes Sociales -->
    <meta property="og:title" content="@yield('og_title', $defaultTitle)">
    <meta property="og:description" content="@yield('og_description', $defaultDescription)">
    <meta property="og:image" content="@yield('og_image', $defaultImage)">
    <meta property="og:url" content="@yield('og_url', $canonicalUrl)">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:locale" content="es_PE">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('twitter_title', $defaultTitle)">
    <meta name="twitter:description" content="@yield('twitter_description', $defaultDescription)">
    <meta name="twitter:image" content="@yield('twitter_image', $defaultImage)">
    
    <!-- Enlace canónico -->
    <link rel="canonical" href="@yield('canonical_url', $canonicalUrl)">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ brandingImage('favicon_url', 'favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ brandingImage('favicon_url', 'favicon.ico') }}">

    <!-- Enlace a Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

    <!-- Carga de FontAwesome para iconos -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <!-- Enlace al archivo de estilos personalizado -->
    <link rel="stylesheet" href="{{ asset('css/styles.css?v=' . time()) }}">
    <!-- Enlace a Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@600;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/whatsapp.css') }}">
    <link rel="stylesheet" href="{{ asset('css/paquetes.css') }}">

    <!-- Variables CSS de Branding -->
    {!! brandingCss() !!}

    <!-- Estilos personalizados -->
    @yield('head') <!-- Permitirá agregar contenido extra en las vistas que lo extienden -->
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a href="/">
                <img src="{{ brandingImage('logo_url', 'imagenes/logo.webp') }}" height="48"
                    alt="{{ $branding->nombre_empresa }}">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="/">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('blog') ? 'active' : '' }}" href="/blog">Nosotros</a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('rutas.tipo') ? 'active' : '' }}"
                            href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                            Tours
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('rutas.tipo', ['tipo' => 'Diarios']) }}">Diarios</a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('rutas.tipo', ['tipo' => 'Weekend']) }}">Fin de semana</a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('renta-cars') ? 'active' : '' }}" href="/renta-cars">
                            Renta de Cars
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido de la página -->
    @yield('plantilla') <!-- Sección para insertar contenido específico de cada vista -->
    <!-- Menú de íconos  ['icon' => 'envelope', 'text' => 'Contacto', 'url' => '#'], -->
    <div class="menu-section hidden lg:flex mb-5">
        <div class="menu-item"><i class="fas fa-home"></i><a href="/">Inicio</a></div>
        <div class="menu-item"><i class="fas fa-book"></i><a href="/blog">Nosotros</a></div>
        <div class="menu-item"><i class="fas fa-road"></i><a class="nav-link"
                href="{{ route('rutas.tipo', ['tipo' => 'Diarios']) }}">Diarios</a></div>
        <div class="menu-item"><i class="fas fa-hiking"></i><a class="nav-link"
                href="{{ route('rutas.tipo', ['tipo' => 'Weekend']) }}">Fin de semana</a></div>
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
                                <img src="{{ brandingImage('logo_animation_url') }}"
                                    alt="{{ $branding->nombre_empresa }}" class="img-fluid"
                                    style="max-width: 100px; height: auto;">
                                <img src="{{ brandingImage('certificacion_url') }}" alt="Certificado" class="img-fluid"
                                    style="max-width: 70px; height: auto;">
                            </div>

                            <!-- Llamado a la acción -->
                            <div class="cta-container text-center mt-1">
                                <h3 class="text-xl font-bold">Ayni Forest</h3>
                                <a class="text-white block mb-2">¡Diceñando tu próxima aventura!</a>

                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $branding->whatsapp_numero) }}"
                                    target="_blank" class="btn text-white inline-block mt-4"
                                    style="background-color: {{ $branding->color_secundario }}; border-color: {{ $branding->color_secundario }};">
                                    <i class="fab fa-whatsapp"></i> ¡Reserva ahora!
                                </a>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <h3 class="text-xl font-bold mt-4">Soporte</h3>
                            <ul class="list-unstyled">
                                <p><a href="https://n9.cl/1bkel">Términos y condiciones</a></p>
                                <p><a href="#" class="text-white">Políticas de privacidad</a></p>
                                <p><a href="https://reclamos.outdoorexpeditionspe.com/">Libro de reclamaciones</a></p>
                                <p><a href="#">Código ESNNA</a></p>
                                <p><a href="#">Certificaciones</a></p>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h3 class="text-xl font-bold mt-4">Contáctanos</h3>
                            <p>
                                <<i class="fas fa-phone text-white "></i>
                                    <a href="https://acortar.link/vcswna" target="_blank">+51-933 329 650</a>
                            </p>

                            <p>
                                <i class="fas fa-envelope"></i>
                                <a href="mailto:{{ $branding->email_contacto }}">{{ $branding->email_contacto }}</a>
                            </p>

                            <p>
                                <i class="fab fa-whatsapp"></i>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $branding->whatsapp_numero) }}"
                                    target="_blank">Escríbenos al WhatsApp</a>
                            </p>

                            <h6 class="text-xl font-bold mt-4">Síguenos en:</h6>

                            <div class="d-flex justify-content-center gap-4 fs-4">
                                @if ($branding->facebook_url)
                                    <a href="{{ $branding->facebook_url }}" target="_blank" title="Facebook">
                                        <i class="fab fa-facebook text-primary"></i>
                                    </a>
                                @endif
                                @if ($branding->instagram_url)
                                    <a href="{{ $branding->instagram_url }}" target="_blank" title="Instagram">
                                        <i class="fab fa-instagram text-danger"></i>
                                    </a>
                                @endif
                                @if ($branding->tiktok_url)
                                    <a href="{{ $branding->tiktok_url }}" target="_blank" title="TikTok">
                                        <i class="fab fa-tiktok" style="color: white;"></i>
                                    </a>
                                @endif
                                @if ($branding->youtube_url)
                                    <a href="{{ $branding->youtube_url }}" target="_blank" title="YouTube">
                                        <i class="fab fa-youtube text-danger"></i>
                                    </a>
                                @endif

                            </div>
                        </div>

                    </div>
                </div>
                <p style="font-size: 10px;" class="text-white-100 mt-4">
                    <span class="text-white">&copy; {{ $branding->nombre_empresa ?? 'Outdoor Expeditions' }}. Todos
                        los derechos reservados | Desarrollado por:J & M Developers</span>
                </p>

            </footer>
        </div>
    </section>

    <!-- Scripts --><script src="https://elfsightcdn.com/platform.js" async></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const packagesPerPage = 8; // Número de paquetes por página
        let currentPage = 1; // Página actual
        // Efecto Reveal al hacer scroll
        const revealElements = () => {
            const reveals = document.querySelectorAll('.reveal');
            reveals.forEach(el => {
                const windowHeight = window.innerHeight;
                const revealTop = el.getBoundingClientRect().top;
                const revealPoint = 150;

                if (revealTop < windowHeight - revealPoint) {
                    el.style.opacity = "1";
                    el.style.transform = "translateY(0)";
                }
            });
        };


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

        window.addEventListener('scroll', revealElements);
    </script>
    @yield('scripts')


    {{-- WhatsApp Chat Widget --}}
    <!-- Cuadro flotante con solo el ícono de WhatsApp -->
    <div class="whatsapp-chat" id="whatsappChat">
        <a href="javascript:void(0)" id="openModal">
            <img src="{{ asset('imagenes/whatsapp-logo.png') }}" alt="WhatsApp" class="whatsapp-logo">
        </a>
    </div>

    <!-- Modal (cuadro emergente) -->
    <div class="modal whatsapp-modal" id="whatsappModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header del modal con color verde -->
                <div class="modal-header bg-success text-white">
                    <h6 class="modal-title">Inicie una conversación</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        id="closeModal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ $branding->descripcion_corta ?? 'Estamos en línea las 24 horas, ¿Tienes alguna pregunta o necesitas más información sobre nuestros tours?' }}
                    </p>
                    <div class="d-flex justify-content-center">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $branding->whatsapp_numero) }}"
                            target="_blank" class="btn btn-success">¡Chatea ahora!</a>
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

        document.addEventListener('DOMContentLoaded', () => {
            // 1. Efecto de cambio en Navbar al hacer scroll
            const navbar = document.querySelector('.navbar');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
            });

            // 2. Animación escalonada (Stagger) para los paquetes
            const packages = document.querySelectorAll('.package');
            packages.forEach((pkg, index) => {
                pkg.style.animationDelay = `${index * 0.1}s`;
            });

            // 3. Hover dinámico en los botones (efecto de brillo)
            const btns = document.querySelectorAll('.package-btn');
            btns.forEach(btn => {
                btn.addEventListener('mousemove', (e) => {
                    const rect = btn.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    btn.style.setProperty('--x', `${x}px`);
                    btn.style.setProperty('--y', `${y}px`);
                });
            });
        });
    </script>


</body>

</html>
