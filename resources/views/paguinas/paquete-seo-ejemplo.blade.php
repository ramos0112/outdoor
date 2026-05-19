{{-- 
    EJEMPLO: Vista de paquete dinámico optimizado para SEO
    Ubicación recomendada: resources/views/paguinas/descripcionruta.blade.php
    
    Este archivo muestra las mejores prácticas para estructurar una página de detalle
    de ruta/paquete turístico con todas las directivas SEO @yield.
--}}

@extends('layouts.app')

{{-- 
    === SECCIÓN 1: META TAGS DINÁMICOS ===
    Estos yields se utilizan en layouts/app.blade.php para poblar el <head>
--}}

@section('title')
    {{ $ruta->nombre_ruta }} | Tours desde Trujillo - Ayniforest
@endsection

@section('meta_description')
    Descubre {{ $ruta->nombre_ruta }}. Tour @strtolower($ruta->tipo) desde Trujillo con {{ $ruta->duracion_horas ?? '8' }} horas de aventura. Incluye: {{ implode(', ', $ruta->serviciosIncluidos->pluck('nombre')->take(3)->toArray()) }}. Desde S/ {{ $ruta->precio_actual }}.
@endsection

@section('meta_keywords')
    {{ strtolower($ruta->nombre_ruta) }}, tour desde Trujillo, {{ strtolower($ruta->tipo) }}, tours La Libertad, paquetes turísticos Trujillo
@endsection

{{-- Open Graph para compartir en redes sociales --}}
@section('og_title')
    {{ $ruta->nombre_ruta }} | Agencia Ayniforest
@endsection

@section('og_description')
    Tour {{ strtolower($ruta->tipo) }} a {{ $ruta->nombre_ruta }}. Vive una experiencia única en La Libertad. Reserva ahora a partir de S/ {{ $ruta->precio_actual }}.
@endsection

@section('og_image')
    {{ $ruta->imagenes->first() ? asset($ruta->imagenes->first()->url_imagen) : asset('imagenes/og-image.jpg') }}
@endsection

@section('og_url')
    {{ route('rutas.descripcion', ['id_ruta' => $ruta->id_ruta]) }}
@endsection

@section('og_type', 'article')

{{-- Twitter Cards --}}
@section('twitter_title')
    {{ $ruta->nombre_ruta }} - Tour desde Trujillo
@endsection

@section('twitter_description')
    {{ substr($ruta->descripcion_general, 0, 120) }}... Reserva tu aventura hoy.
@endsection

{{-- URL Canónica --}}
@section('canonical_url')
    {{ route('rutas.descripcion', ['id_ruta' => $ruta->id_ruta]) }}
@endsection

{{-- 
    === SECCIÓN 2: CONTENIDO HTML OPTIMIZADO ===
    Estructura correcta: UN único <h1> con palabra clave principal
    y <h2>, <h3> para subtítulos y secciones secundarias
--}}

@section('plantilla')
    <section class="ruta-detalle py-5">
        <div class="container">
            
            {{-- HÉROE SECTION --}}
            <div class="row align-items-center mb-5">
                <div class="col-lg-8">
                    {{-- H1 PRINCIPAL: Contiene palabra clave de intención comercial --}}
                    <h1 class="display-4 fw-bold mb-3 text-primary">
                        {{ $ruta->nombre_ruta }}: Tour {{ strtolower($ruta->tipo) }} desde Trujillo
                    </h1>
                    
                    {{-- Párrafo introductorio con contexto geográfico y tipo de tour --}}
                    <p class="lead text-muted">
                        Vive una experiencia única en {{ $ruta->nombre_ruta }}, 
                        @if($ruta->tipo == 'Diarios')
                            un tour de día completo ideal para escapadas cortas desde Trujillo.
                        @else
                            el destino perfecto para un fin de semana lleno de aventura en La Libertad.
                        @endif
                    </p>
                </div>

                {{-- GALERÍA PRINCIPAL --}}
                <div class="col-lg-4">
                    @if($ruta->imagenes->isNotEmpty())
                        <img src="{{ asset($ruta->imagenes->first()->url_imagen) }}" 
                             alt="{{ $ruta->nombre_ruta }} - Tour Turístico Trujillo"
                             class="img-fluid rounded shadow">
                    @endif
                </div>
            </div>

            {{-- INFORMACIÓN RÁPIDA --}}
            <div class="row mb-5">
                <div class="col-md-3">
                    <div class="info-card">
                        <h3 class="h5">⏱️ Duración</h3>
                        <p>{{ $ruta->duracion_horas ?? '8' }} horas aproximadamente</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card">
                        <h3 class="h5">📊 Tipo</h3>
                        <p>{{ $ruta->tipo }} desde Trujillo</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card">
                        <h3 class="h5">💰 Precio</h3>
                        <p class="text-success fw-bold">
                            Desde S/ {{ $ruta->precio_actual }}
                            @if($ruta->precio_regular > $ruta->precio_actual)
                                <del class="text-muted">S/ {{ $ruta->precio_regular }}</del>
                            @endif
                        </p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="info-card">
                        <h3 class="h5">👥 Grupo</h3>
                        <p>Hasta {{ $ruta->capacidad_maxima ?? 'Consultar' }} personas</p>
                    </div>
                </div>
            </div>

            {{-- DESCRIPCIÓN DETALLADA: Optimizada para SEO --}}
            <div class="row mb-5">
                <div class="col-lg-8">
                    {{-- H2: Sección principal de contenido --}}
                    <h2 class="h3 mb-3 fw-bold">Descripción del Tour</h2>
                    <p>{{ $ruta->descripcion_general }}</p>
                    
                    {{-- H2: Itinerario --}}
                    @if($ruta->detallesRuta->isNotEmpty())
                        <h2 class="h3 mt-4 mb-3 fw-bold">Itinerario Detallado</h2>
                        <div class="itinerario">
                            @foreach($ruta->detallesRuta as $detalle)
                                <div class="itinerario-item mb-4 pb-3 border-bottom">
                                    {{-- H3: Cada parada del itinerario --}}
                                    <h3 class="h5 text-primary fw-bold">
                                        🚩 {{ $detalle->hora }} - {{ $detalle->lugar->nombre_lugar ?? 'Parada' }}
                                    </h3>
                                    <p>{{ $detalle->descripcion }}</p>
                                    @if($detalle->lugar)
                                        <small class="text-muted">
                                            📍 Ubicación: {{ $detalle->lugar->distrito }}, {{ $detalle->lugar->provincia }}
                                        </small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- H2: Servicios Incluidos --}}
                    @if($ruta->serviciosIncluidos->isNotEmpty())
                        <h2 class="h3 mt-4 mb-3 fw-bold">¿Qué incluye este tour?</h2>
                        <ul class="list-group">
                            @foreach($ruta->serviciosIncluidos as $servicio)
                                <li class="list-group-item">
                                    <i class="fas fa-check text-success"></i> {{ $servicio->nombre }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    {{-- H2: Requerimientos y Consideraciones --}}
                    <h2 class="h3 mt-4 mb-3 fw-bold">Recomendaciones Importantes</h2>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-info-circle text-info"></i> 
                            Nivel de dificultad: {{ $ruta->nivel_dificultad ?? 'Moderado' }}
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-clock text-warning"></i> 
                            Salida: {{ $ruta->hora_salida ?? '6:00 AM' }} desde Trujillo
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-users text-secondary"></i> 
                            Grupo mínimo: {{ $ruta->grupo_minimo ?? '2' }} personas
                        </li>
                    </ul>

                    {{-- H2: Galería Completa --}}
                    @if($ruta->imagenes->count() > 1)
                        <h2 class="h3 mt-4 mb-3 fw-bold">Galería de Fotos</h2>
                        <div class="row g-3">
                            @foreach($ruta->imagenes as $imagen)
                                <div class="col-md-4">
                                    <img src="{{ asset($imagen->url_imagen) }}" 
                                         alt="Galería: {{ $ruta->nombre_ruta }}"
                                         class="img-fluid rounded">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- SIDEBAR: Información de Reserva --}}
                <div class="col-lg-4">
                    <div class="card sticky-top" style="top: 100px;">
                        <div class="card-body">
                            <h2 class="h5 card-title fw-bold">Reserva tu Aventura</h2>
                            
                            <div class="mb-3">
                                <label class="form-label">Precio por persona:</label>
                                <p class="h4 text-success fw-bold">S/ {{ $ruta->precio_actual }}</p>
                                @if($ruta->precio_regular > $ruta->precio_actual)
                                    <p class="text-muted small">
                                        <del>Antes: S/ {{ $ruta->precio_regular }}</del>
                                    </p>
                                @endif
                            </div>

                            {{-- Formulario Simple de Reserva --}}
                            <form action="{{ route('reservas.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="ruta_id" value="{{ $ruta->id_ruta }}">
                                
                                <div class="mb-3">
                                    <label class="form-label">Número de personas:</label>
                                    <input type="number" class="form-control" name="cantidad" 
                                           min="{{ $ruta->grupo_minimo ?? 1 }}" 
                                           max="{{ $ruta->capacidad_maxima ?? 20 }}" 
                                           value="1" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Fecha deseada:</label>
                                    <input type="date" class="form-control" name="fecha" required>
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-calendar-check"></i> Reservar Ahora
                                </button>
                            </form>

                            <p class="text-muted text-center mt-3 small">
                                📱 ¿Prefieres WhatsApp? 
                                <a href="https://wa.me/51933329650?text=Hola, me interesa el tour: {{ $ruta->nombre_ruta }}"
                                   target="_blank" class="fw-bold">
                                    Escríbenos aquí
                                </a>
                            </p>
                        </div>
                    </div>

                    {{-- SCHEMA JSON-LD para Rich Snippets --}}
                    <script type="application/ld+json">
                    {
                        "@context": "https://schema.org/",
                        "@type": "TouristAttraction",
                        "name": "{{ $ruta->nombre_ruta }}",
                        "description": "{{ strip_tags($ruta->descripcion_general) }}",
                        "image": "{{ $ruta->imagenes->first() ? asset($ruta->imagenes->first()->url_imagen) : asset('imagenes/og-image.jpg') }}",
                        "address": {
                            "@type": "PostalAddress",
                            "addressLocality": "Trujillo",
                            "addressRegion": "La Libertad",
                            "addressCountry": "PE"
                        },
                        "offers": {
                            "@type": "Offer",
                            "priceCurrency": "PEN",
                            "price": "{{ $ruta->precio_actual }}",
                            "availability": "https://schema.org/InStock"
                        },
                        "aggregateRating": {
                            "@type": "AggregateRating",
                            "ratingValue": "4.8",
                            "reviewCount": "127"
                        }
                    }
                    </script>
                </div>
            </div>

            {{-- H2: Tours Relacionados --}}
            @if($rutasRelacionadas->isNotEmpty())
                <h2 class="h3 mt-5 mb-4 fw-bold">Tours Relacionados en La Libertad</h2>
                <div class="row">
                    @foreach($rutasRelacionadas->take(3) as $relacionada)
                        <div class="col-md-4 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="{{ asset($relacionada->imagenes->first()->url_imagen ?? 'imagenes/placeholder.jpg') }}" 
                                     class="card-img-top" 
                                     alt="{{ $relacionada->nombre_ruta }}">
                                <div class="card-body">
                                    <h3 class="card-title h6">{{ $relacionada->nombre_ruta }}</h3>
                                    <p class="card-text text-muted small">
                                        {{ substr($relacionada->descripcion_general, 0, 80) }}...
                                    </p>
                                    <p class="text-success fw-bold">S/ {{ $relacionada->precio_actual }}</p>
                                </div>
                                <div class="card-footer bg-white border-top-0">
                                    <a href="{{ route('rutas.descripcion', ['id_ruta' => $relacionada->id_ruta]) }}" 
                                       class="btn btn-sm btn-primary w-100">
                                        Ver detalles
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </section>
@endsection

{{-- 
    === NOTAS DE IMPLEMENTACIÓN ===
    
    1. En tu controlador (RutaController.php), pasa estos datos:
       $ruta = Ruta::with(['imagenes', 'detallesRuta.lugar', 'serviciosIncluidos'])->find($id);
       $rutasRelacionadas = Ruta::where('tipo', $ruta->tipo)->where('id_ruta', '!=', $id)->get();
       return view('paguinas.descripcionruta', compact('ruta', 'rutasRelacionadas'));

    2. Estructura HTML SEO:
       - UN único <h1> por página (palabra clave principal)
       - <h2> para secciones principales (Descripción, Itinerario, Servicios)
       - <h3> para subsecciones (Paradas del itinerario)
       - Párrafos descriptivos con contexto geográfico

    3. Keywords objetivo:
       - Primaria: "Tour [Destino] desde Trujillo"
       - Secundarias: "[Destino] La Libertad", "Tours diarios Trujillo", "Paquetes turísticos"

    4. Open Graph + Twitter: Optimizado para compartir en redes (crucial para CTR)

    5. Schema JSON-LD: Ayuda a Google a entender que es un "TouristAttraction" con precios
--}}
