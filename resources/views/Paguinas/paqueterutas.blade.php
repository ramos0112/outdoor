<!-- resources/views/paguinas/paqueterutas.blade.php -->
<section class="packages">
    <div class="container ">
        <h1 class="text-black text-center font-bold">
            @isset($tipo)
                Explora tours de {{ ucfirst($tipo) }} desde Trujillo
            @else
                Explora todas las rutas disponibles desde Trujillo
            @endisset
        </h1>
        <section class="packages-grid">
            @foreach ($rutas as $ruta)
                <div class="package">
                    <img src="{{ asset($ruta->imagenes->first()->url_imagen ?? 'storage/imagenes/default.jpg') }}"
                        alt="{{ $ruta->nombre_ruta }}">
                    <h3 class="package-title">{{ $ruta->nombre_ruta }}</h3>
                    <p>{{ $ruta->descripcion_general }}</p>
                    <p class="price">{{ strtoupper($ruta->tipo) }} <br>
                        Desde: <del>S/ {{ $ruta->precio_regular }}</del>
                        <strong>S/ {{ $ruta->precio_actual }}</strong>
                    </p>
                    <a href="{{ route('rutas.descripcion', ['id_ruta' => $ruta->id_ruta]) }}" class="package-btn">Ver
                        más</a>
                </div>
            @endforeach
        </section>
        <div class="pagination"></div>
    </div>
</section>
