<!-- resources/views/paguinas/home.blade.php -->
@extends('layouts.app')

@section('title', 'Home')

@section('plantilla')
    <!-- Sección Hero -->
    <section class="hero text-center">

        <h1 class="text-3xl font-bold sm:text-2xl md:text-4xl">
            Empieza a descubrir <span class="text-danger">La Libertad</span>
        </h1>
        <p>Tours Full Days todos los días</p>

        <!-- Menú de íconos -->
        <div class="menu-section d-none d-lg-flex justify-content-center gap-4 mb-5">
            @php
                $menu = [
                    ['icon' => 'home', 'text' => 'Home', 'url' => '/'],
                    ['icon' => 'road', 'text' => 'Rutas', 'url' => '/ruta'],
                    ['icon' => 'book', 'text' => 'Blog', 'url' => '#'],
                    ['icon' => 'hiking', 'text' => 'Trekking', 'url' => '#'],
                    ['icon' => 'envelope', 'text' => 'Contacto', 'url' => '#'],
                ];
            @endphp
            @foreach ($menu as $item)
                <div class="menu-item">
                    <i class="fas fa-{{ $item['icon'] }}"></i>
                    <a href="{{ $item['url'] }}">{{ $item['text'] }}</a>
                </div>
            @endforeach
        </div>
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
                    <img src="https://toursentrujillo.com/wp-content/uploads/2022/01/puenting.jpg" alt="Cascas Historia"
                        class="img-fluid rounded">
                </div>
                <div class="col-md-7">
                    <p class="fs-6">
                        Cascas posee una rica historia que se remonta al periodo formativo, con evidencias arqueológicas
                        como petroglifos y cerámicas de diversas culturas antiguas. Durante la época virreinal, se fundó
                        como “San Gabriel de Caxcax” y ha sido un punto estratégico de tránsito entre la sierra y la costa.
                        Personajes históricos como Bolívar y Humboldt pasaron por allí. Hoy, Cascas es una ciudad con
                        profundas tradiciones culturales y religiosas, destacando festividades como la fiesta patronal en
                        honor a la Virgen del Rosario, los carnavales y la Feria Regional de la Uva. Gracias a su clima
                        privilegiado, produce uva dos veces al año, siendo un importante centro vitivinícola en el Perú, con
                        bodegas artesanales reconocidas por sus vinos y piscos de calidad.
                    </p>
                </div>
            </div>

            <!-- Fila de bloques de imagen -->
            <div class="row gx-4 gy-3">
                @php
                    $bloques = [
                        [
                            'verticales' => [
                                'https://aritoursperu.com/wp-content/uploads/2024/08/cascas-extremo.jpg',
                                'https://www.elbrujo.pe/storage/noticias/July2023/Visita%20el%20Complejo%20Arqueol%C3%B3gico%20El%20Brujo.jpg',
                                'https://i.pinimg.com/236x/9e/9a/c8/9e9ac8e150142bc22726933d792ea75a.jpg',
                            ],
                            'horizontales' => [
                                'https://toursentrujillo.com/wp-content/uploads/2022/01/puenting.jpg',
                                'https://www.venta-vinosarcascas.com/wp-content/uploads/2023/07/IMG_0040-scaled.jpg',
                                'https://blog.redbus.pe/wp-content/uploads/2021/09/playa-cherrepe-e1631229639128-1280x720.jpg',
                                'https://explorandomaravillas.com/wp-content/uploads/chan-chan-en-trujillo-3.jpg',
                                'https://viajeronline.com/wp-content/uploads/que-ver-hacer-visitar-en-trujillo-la-libertad-sitios-turisticos-peru-catedral-1.png',
                            ],
                            'rutas' => '/ruta',
                        ],
                        [
                            'verticales' => [
                                'https://portal.andina.pe/EDPfotografia3/Thumbnail/2024/05/30/001068846W.jpg',
                                'https://explorandomaravillas.com/wp-content/uploads/plaza-de-armas-trujillo.jpg',
                                'https://explorandomaravillas.com/wp-content/uploads/plaza-de-armas-de-trujillo-2.jpg',
                            ],
                            'horizontales' => [
                                'https://portal.andina.pe/EDPFotografia3/thumbnail/2024/05/30/001068848M.jpg',
                                'https://portal.andina.pe/EDPFotografia3/thumbnail/2024/05/30/001068817M.jpg',
                                'https://www.elperuano.pe/fotografia//thumbnail/2019/11/14/000067422M.jpg',
                                'https://i.ytimg.com/vi/rUmWc-etR-4/maxresdefault.jpg',
                                'https://www.ytuqueplanes.com/imagenes//fotos/novedades/alerta-MINCUL.webp',
                            ],
                            'rutas' => '/trekking',
                        ],
                    ];
                @endphp
                @foreach ($bloques as $index => $bloque)
                    <div class="col-md-6 mb-4">
                        <div class="bg-white bg-opacity-75 p-2 rounded shadow-sm h-100" style="height: 400px;">
                            <div class="row g-2 h-100" data-verticals='@json($bloque['verticales'])'
                                data-horizontales='@json($bloque['horizontales'])' id="bloque-{{ $index }}">

                                <div class="col-6">
                                    <div class="img-vertical h-100 w-100 rounded overflow-hidden"></div>
                                </div>
                                <div class="col-6 d-flex flex-column gap-2">
                                    <div class="img-horizontal flex-fill rounded overflow-hidden"></div>
                                    <div class="img-horizontal flex-fill rounded overflow-hidden"></div>
                                </div>
                            </div>
                            <a href="{{ $bloque['rutas'] }}" class="btn btn-success mt-3">Ver Más...</a>
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
            const verticalImages = JSON.parse(bloque.dataset.verticals);
            const horizontalImages = JSON.parse(bloque.dataset.horizontales);
            const verticalContainer = bloque.querySelector('.img-vertical');
            const horizontalContainers = bloque.querySelectorAll('.img-horizontal');

            function getRandomImage(images) {
                return images[Math.floor(Math.random() * images.length)];
            }

            function updateImages() {
                const verticalSrc = getRandomImage(verticalImages);
                verticalContainer.innerHTML = `<img src="${verticalSrc}" class="img-fluid w-100 h-100 object-fit-cover" alt="Imagen vertical">`;

                const shuffledHorizontals = horizontalImages.sort(() => 0.5 - Math.random()).slice(0, 2);
                horizontalContainers.forEach((container, i) => {
                    container.innerHTML = `<img src="${shuffledHorizontals[i]}" class="img-fluid w-100 h-100 object-fit-cover" alt="Imagen horizontal">`;
                });
            }

            updateImages();
            setInterval(updateImages, updateInterval);
        });
    });
</script>
