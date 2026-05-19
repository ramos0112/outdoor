<!-- resources/views/paguinas/home.blade.php -->
@extends('layouts.app')

@section('title', 'Ayniforest | Agencia de Viajes y Turismo en Trujillo, Perú')

@section('meta_description', 'Ayniforest ofrece paquetes turísticos y Full Days desde Trujillo. Descubre rutas de aventura y cultura en La Libertad con reserva segura y pagos por Mercado Pago.')

@section('meta_keywords', 'tours Trujillo, paquetes turísticos Trujillo, Full Day La Libertad, agencia de viajes, reservas online')

@section('og_title', 'Ayniforest - Tours y Full Days desde Trujillo')
@section('og_description', 'Reserva tours y paquetes turísticos desde Trujillo en Ayniforest. Experiencias de naturaleza, cultura y aventura en La Libertad.')
@section('og_image', asset('imagenes/og-image.jpg'))
@section('og_url', url()->current())
@section('canonical_url', url()->current())

@section('twitter_title', 'Ayniforest - Agencia de Viajes en Trujillo')
@section('twitter_description', 'Tours y paquetes turísticos desde Trujillo. Reserva Full Days y aventuras en La Libertad con Ayniforest.')

@section('plantilla')
 
    <link rel="stylesheet" href="{{ asset('css/paquetes.css') }}">

    <!-- Sección Hero -->
    <section class="hero text-center">

        <h1 class="text-3xl font-bold sm:text-2xl md:text-4xl">
            Empieza a descubrir <span>La Libertad</span>
        </h1>
        <p class="text-white">
            Vive nuevas experiencias, descubre paisajes únicos y encuentra tu próxima aventura.
        </p>
    </section>

    <!-- Espaciador (opcional si hay más contenido abajo) -->
    <section class="bg-dark py-3">
        <div class="container d-flex justify-content-center"></div>
    </section>

    <!-- Sección informativa -->
    <section class="py-5">
        <div class="container">
            <!-- Fila con imagen y texto -->
            <div class="row align-items-center mb-5">
                <div class="col-md-5">
                    <img src="{{ brandingImage('nosotros_url') }}" class="img-fluid" alt="Nosotros">
                </div>
                <div class="col-md-7">
                    <h2>¿Quiénes Somos?</h2>
                    <p class="fs-6">
                        En Ayni Forest creemos que cada viaje es una oportunidad para descubrir nuevos paisajes,
                        conectar con la naturaleza y vivir experiencias que permanezcan para siempre.
                        Como agencia de viajes y tour operadora 100% local con sede en Trujillo, La Libertad,
                        trabajamos para ofrecer experiencias auténticas, organizadas y seguras,
                        adaptadas a cada viajero y diseñadas para mostrar la esencia del norte peruano.
                        <br>
                        Nos esforzamos por cuidar cada detalle de nuestras rutas, brindando atención cercana, 
                        buena energía y aventuras que combinan naturaleza, cultura y emoción. Más que planificar viajes,
                        buscamos crear momentos inolvidables y hacer que cada destino se convierta en una experiencia única.
                        Hoy creamos experiencias, mañana serán recuerdos para toda la vida.

                    </p>
                </div>
            </div>

            <!-- Fila de bloques de imagen -->
            <div class="row gx-4 gy-3 reveal">
                @php
                    $bloques = [
                        [
                            'titulo' => 'Tours Diarios',
                            'imagenes' => $rutasDiarios,
                            'ruta' => route('rutas.tipo', ['tipo' => 'Diarios']),
                        ],
                        [
                            'titulo' => 'Tours fin de semana',
                            'imagenes' => $rutasWeekend,
                            'ruta' => route('rutas.tipo', ['tipo' => 'Weekend']),
                        ],
                    ];
                @endphp
                @foreach ($bloques as $index => $bloque)
                    @php
                        $imagenes = $bloque['imagenes']
                            ->pluck('imagenes')
                            ->flatten()
                            ->pluck('url_imagen')
                            ->filter()
                            ->map(fn($img) => asset($img))
                            ->take(10)
                            ->values()
                            ->toArray();

                    @endphp

                    <div class="col-md-6 mb-4">
                        <div class="bg-white bg-opacity-75 p-3 rounded shadow-sm h-100 text-center">
                            <h2 class="fw-bold mb-3">{{ $bloque['titulo'] }}</h2>

                            <div class="row g-2 justify-content-center" data-imgs='@json($imagenes)'
                                id="bloque-{{ $index }}">
                                @for ($i = 0; $i < 4; $i++)
                                    <div class="col-6">
                                        <div class="ratio ratio-4x3 rounded overflow-hidden img-slot"
                                            style="max-width: 245px; margin: 0 auto;">
                                            <img src="" class="w-100 h-100 object-fit-cover img-fluid"
                                                alt="Imagen">
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ $bloque['ruta'] }}" class="btn package-btn px-4 py-1">Ver más</a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </section>
    @include('paguinas.paqueterutas')
@endsection

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const updateInterval = 5000; // 5 segundos

        document.querySelectorAll('[id^="bloque-"]').forEach(bloque => {
            const imagenes = JSON.parse(bloque.dataset.imgs);
            const slots = bloque.querySelectorAll('.img-slot');

            function getRandomImages(images, count) {
                return [...images].sort(() => 0.5 - Math.random()).slice(0, count);
            }

            // Reemplaza tu función updateImages dentro del script de home.blade.php con esta:
            function updateImages() {
                const randomImgs = getRandomImages(imagenes, 4);

                slots.forEach((slot, i) => {
                    const imgElement = slot.querySelector('img');

                    if (!randomImgs[i]) return; // 🛑 evita undefined

                    imgElement.style.opacity = '0';

                    setTimeout(() => {
                        imgElement.src = randomImgs[i];
                        imgElement.onload = () => {
                            imgElement.style.opacity = '1';
                        };
                    }, 800);
                });
            }

            updateImages();
            setInterval(updateImages, updateInterval);
        });
    });
    document.addEventListener('DOMContentLoaded', () => {
        const section = document.querySelector('.row.align-items-center.mb-5');
        const textElement = section.querySelector('.fs-6');
        const originalText = textElement.innerHTML; // Guardamos el texto con sus <br>

        // Preparamos el elemento de texto
        textElement.innerHTML = '';
        textElement.classList.add('typing-cursor');
        const isMobile = window.innerWidth < 768;
        const typingSpeed = isMobile ? 5 : 15
        const typewriter = (element, text) => {
            let i = 0;
            element.style.visibility = 'visible';

            // Función recursiva para escribir
            const type = () => {
                if (i < text.length) {
                    // Si detectamos un tag HTML (como <br>), lo saltamos para que no se rompa la lógica
                    if (text.charAt(i) === '<') {
                        i = text.indexOf('>', i) + 1;
                    } else {
                        i++;
                    }
                    element.innerHTML = text.substring(0, i);
                    setTimeout(type, typingSpeed); // Velocidad de escritura (ms)
                } else {
                    element.classList.remove('typing-cursor');
                    element.style.borderRight = 'none';
                }
            };
            type();
        };

        // Observer para disparar ambos efectos al hacer scroll
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Activamos la imagen (CSS)
                    section.classList.add('reveal-active');

                    // Activamos el texto (JS)
                    typewriter(textElement, originalText);

                    // Dejamos de observar para que no se repita
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.3
        }); // Se activa cuando el 30% de la sección es visible

        observer.observe(section);
    });
</script>
