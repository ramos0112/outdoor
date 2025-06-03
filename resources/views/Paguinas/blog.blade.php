@extends('layouts.app')

@section('title', 'Blog')
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

@section('plantilla')
<section class="bg-light py-5">
    <div class="container">
        <h1 class="text-center  mb-4 text-danger">Descubre Trujillo con Nuestra Agencia de Turismo</h1>
        <p class="lead text-center">
            Somos una agencia de turismo especializada en brindar experiencias inolvidables en la ciudad de Trujillo y sus alrededores. Conoce la historia, cultura y naturaleza de esta joya del norte peruano.
        </p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <img src="https://content.emarket.pe/common/collections/standard/bb/48/bb481695-657c-486c-bb3d-856651b33959.jpg" class="img-fluid rounded shadow" alt="Turismo Trujillo">
            </div>
            <div class="col-md-6">
                <h2 class="text-success">¿Quiénes Somos?</h2>
                <p>
                    Somos una agencia con más de 10 años de experiencia en el sector turístico, ofreciendo tours personalizados, excursiones full day y paquetes culturales. Nuestro equipo está conformado por guías locales, apasionados por mostrar lo mejor de Trujillo.
                </p>
                <p>
                    Contamos con transporte seguro, guías certificados y un profundo conocimiento de los destinos históricos como Chan Chan, Huaca del Sol y la Luna, y la mágica playa de Huanchaco.
                </p>
            </div>
        </div>

        <h3 class="text-center text-dark my-4">Servicios que Ofrecemos</h3>
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-landmark fa-3x text-warning mb-3"></i>
                        <h5 class="card-title">City Tours en Trujillo</h5>
                        <p class="card-text">Recorre el centro histórico, la catedral, y los balcones coloniales con guías especializados.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-archway fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Tours Arqueológicos</h5>
                        <p class="card-text">Visita Chan Chan, la ciudad de barro más grande del mundo, y las huacas del Moche.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <div class="card-body">
                        <i class="fas fa-campground fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Turismo de Aventura</h5>
                        <p class="card-text">Disfruta del parapente, trekking en la campiña de Moche y paseos en caballo en Huanchaco.</p>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="text-center text-dark my-5">Lo que Dicen Nuestros Clientes</h3>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="testimonial bg-white p-4 rounded shadow-sm">
                    <blockquote class="blockquote text-center">
                        <p class="mb-0">"¡Increíble experiencia! Gracias a esta agencia conocimos lugares que ni imaginábamos. Los guías son muy amables y profesionales. Chan Chan fue impresionante."</p>
                        <footer class="blockquote-footer mt-2">María López, turista de Argentina</footer>
                    </blockquote>
                </div>
            </div>
        </div>

        <div class="text-center mt-5">
            <a href="https://wa.link/0037yw" target="_blank" class="btn btn-lg btn-danger">¡Contáctanos y planea tu aventura!</a>
      

            
        </div>
    </div>
</section>
@endsection
