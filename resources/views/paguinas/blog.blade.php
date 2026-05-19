@extends('layouts.app')

@section('title', 'Blog')

@section('meta_description', 'Artículos, noticias y consejos sobre turismo en La Libertad y Trujillo. Encuentra guías, recomendaciones y novedades de Ayniforest.')
@section('canonical_url', url()->current())
@section('og_title', 'Blog - Ayniforest')
@section('og_description', 'Noticias y artículos sobre rutas, turismo y consejos en La Libertad.')
@section('og_image', asset('imagenes/logo.webp'))
<link rel="stylesheet" href="{{ asset('css/blog.css') }}">
 
<section class="hero hero-nosotros text-center">
    <h1>
    Descubre quiénes somos
    <span>y por qué elegimos</span>
    </h1>
    <p> 
    Descubre la historia, esencia y pasión que nos inspira
    a mostrar los paisajes más increíbles del norte peruano.
    </p>
</section>
<section class="bg-dark py-3">
    <div class="container d-flex justify-content-center"></div>
</section>

@section('plantilla')
    <section class="bg-light py-5">
        <div class="container">
            <h2>Descubre Trujillo con Nuestra Agencia de Turismo</h2>
            <p class="lead text-center">
                En Ayni Forest creemos que viajar es mucho más que conocer un destino;
                es conectar con la naturaleza, descubrir nuevas experiencias y crear
                recuerdos que permanecen para siempre.
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="{{ brandingImage('historia_url') }}" 
                        class="img-fluid rounded shadow" alt="Turismo Trujillo" title="Historia">
                </div>
                <div class="col-md-6" style="text-align: justify;">
                    <h2>Nuestra Historia</h2>
                    <p>
                        Ayni Forest nace con el propósito de diversificar la oferta turística de la 
                        región La Libertad y mostrar destinos que durante mucho tiempo fueron poco 
                        promovidos fuera de Trujillo. La idea surge gracias a un grupo de profesionales 
                        de la carrera de Turismo, quienes identificaron la necesidad de crear experiencias 
                        diferentes, con rutas mejor organizadas, atención más cercana y guías calificados que 
                        permitieran a más personas descubrir la riqueza natural y cultural del norte peruano.
                    </p>
                    <p>
                        Nuestros primeros viajes comenzaron explorando destinos como Cascas, 
                        Salpo y Santiago de Chuco, lugares que en ese momento eran poco conocidos 
                        y que hoy forman parte de las rutas más representativas y solicitadas por 
                        nuestros viajeros. Gracias a ello, hemos logrado posicionar nuevos destinos 
                        turísticos y conectar a más personas con experiencias auténticas y memorables
                    </p>
                    <p>
                        El nombre Ayni Forest nace de la unión de dos conceptos que representan nuestra esencia:
                         “Ayni”, palabra quechua relacionada con reciprocidad, comunidad y conexión; y “Forest”,
                          que representa la naturaleza, aventura y libertad de explorar nuevos paisajes. Con el 
                          paso del tiempo, Ayni Forest ha logrado posicionarse en plataformas como TikTok, Instagram 
                          y Facebook gracias a sus nuevas propuestas, experiencias dinámicas y contenido que inspira 
                          a más personas a viajar y descubrir nuevos destinos
                    </p>
                    <p>
                        Hoy seguimos trabajando para crear experiencias auténticas, organizadas y llenas de buena energía, 
                        promoviendo el turismo responsable y haciendo que cada viaje se convierta en un recuerdo 
                        inolvidable.
                    </p>
                </div>
            </div>
            @include('paguinas.nosotros.filosofia')
            @include('paguinas.nosotros.valores')
            @include('paguinas.nosotros.testimonios')
        </div>
    </section>
@endsection

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
{{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> --}}
<link href="https://fonts.googleapis.com/css2?family=Blantic+Rockybilly&display=swap" rel="stylesheet">


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const valores = document.querySelectorAll('.valor');

        valores.forEach(valor => {
            valor.addEventListener('click', function() {
                this.classList.toggle('open');
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
