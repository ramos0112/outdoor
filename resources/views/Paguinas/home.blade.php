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
                            'titulo' => 'Tours de Aventura',
                            'imagenes' => $rutasAventura,
                            'ruta' => route('rutas.tipo', ['tipo' => 'Aventura']),
                        ],
                        [
                            'titulo' => 'Tours de Trekking',
                            'imagenes' => $rutasTrekking,
                            'ruta' => route('rutas.tipo', ['tipo' => 'Trekking']),
                        ],
                    ];
                @endphp
                @foreach ($bloques as $index => $bloque)
                    @php
                        $imagenes = $bloque['imagenes']
                            ->pluck('imagenes')
                            ->flatten()
                            ->pluck('url_imagen')
                            ->take(10)
                            ->toArray();
                    @endphp

                    <div class="col-md-6 mb-4">
                        <div class="bg-white bg-opacity-75 p-3 rounded shadow-sm h-100 text-center">
                            <h5 class="fw-bold mb-3">{{ $bloque['titulo'] }}</h5>

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
                                <a href="{{ $bloque['ruta'] }}" class="btn btn-success px-4 py-1">Ver más</a>
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

            function updateImages() {
                const randomImgs = getRandomImages(imagenes, 4);
                slots.forEach((slot, i) => {
                    slot.innerHTML =
                        `<img src="${randomImgs[i]}" class="img-fluid w-100 h-100 object-fit-cover" alt="Imagen">`;
                });
            }
            updateImages();
            setInterval(updateImages, updateInterval);
        });
    });
</script>
