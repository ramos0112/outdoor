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
            <h2>Descubre Trujillo con Nuestra Agencia de Turismo</h2>
            <p class="lead text-center">
                Somos una agencia de turismo especializada en brindar experiencias inolvidables en la ciudad de Trujillo y
                sus alrededores. Conoce la historia, cultura y naturaleza de esta joya del norte peruano.
            </p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row align-items-center mb-5">
                <div class="col-md-6">
                    <img src="https://content.emarket.pe/common/collections/standard/bb/48/bb481695-657c-486c-bb3d-856651b33959.jpg"
                        class="img-fluid rounded shadow" alt="Turismo Trujillo">
                </div>
                <div class="col-md-6">
                    <h2>Nuestra Historia</h2>
                    <p>
                        Somos una agencia con más de 10 años de experiencia en el sector turístico, ofreciendo tours
                        personalizados, excursiones full day y paquetes culturales. Nuestro equipo está conformado por guías
                        locales, apasionados por mostrar lo mejor de Trujillo.
                    </p>
                    <p>
                        Contamos con transporte seguro, guías certificados y un profundo conocimiento de los destinos
                        históricos como Chan Chan, Huaca del Sol y la Luna, y la mágica playa de Huanchaco.
                    </p>
                </div>
            </div>

            <h2>Filosofía Empresarial</h2>
            <div class="row text-center">
                <!-- Misión -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">VISIÓN</h5>
                            <p class="card-text">Outdoor Expeditions aspira a ser la agencia de viajes de aventura líder en
                                operar y posicionar servicios turísticos en el mercado peruano e internacional, forjando
                                lazos inseparables entre el desarrollo de las comunidades locales y la sostenibilidad
                                ambiental. Buscamos ser un referente en la promoción de destinos turísticos emergentes y en
                                la creación de experiencias únicas que destaquen por su autenticidad.</p>
                        </div>
                    </div>
                </div>

                <!-- Visión -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">MISIÓN</h5>
                            <p class="card-text">Organizamos experiencias turísticas auténticas y de aventura en la región
                                La Libertad, priorizando los principios de sostenibilidad y turismo responsable en los
                                destinos que operamos. A través de nuestro equipo de trabajo buscamos brindar a nuestros
                                excursionistas y aventureros la mejor calidad de servicio posible, enfocándonos en
                                brindarles vivencias de valor, únicas y de alta calidad.</p>
                        </div>
                    </div>
                </div>
                <!-- Objetivos -->
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Objetivos</h5>
                            <p class="card-text">Descubrir y mostrar el potencial del Perú de una forma diferente,
                                desarrollando tours de aventura y full days que inspiren a nuestros viajeros a lograr una
                                conexión profunda con la naturaleza y los incentiven a salir de su zona de confort. En
                                Outdoor Expeditions mantenemos la firme creencia que todo viaje deja huella, es así que
                                priorizamos que cada aventura sea una oportunidad para aprender algo nuevo sobre nosotros
                                mismos, sobre nuestro entorno y sobre los demás.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="valores-container">
                <h2>VALORES “QUE NOS INSPIRAN”</h2>
                <div class="valor">
                    <div><i class="fas fa-users"></i><strong>CONÓCENOS</strong><span class="icono"><i
                                class="fas fa-chevron-down"></i></span></div>
                    <div class="descripcion">
                        <p>Outdoor Expeditions fue formado el 1ro de Septiembre del 2019 durante el
                        ascenso a Montañas Liberteñas en Perú, motivados por el profundo deseo de abrir caminos al mundo.
                        Como el primer operador turístico de trekking en la región hemos guiado a distintos excursionistas
                        por rutas recién aperturadas en los Andes liberteños, tales como Camino Inca “Tramo Escalerilla”,
                        Pico Urpillao, Pico Huaylillas, Cumbre Apu Shallas, Bosque de Salmuche, 7 lagunas de Quiruvilca,
                        entre muchas otras, en expediciones que van desde los 2 a 3 días. </p>
                        <p> recorrido un largo camino, teniendo como nombre inicial “Club Buenas Rutas”, sin embargo
                        nuestra esencia Outdoor se ha mantenido desde nuestra primera salida en el 2019 hasta el 22 de Abril
                        del 2023 que empezamos a operar con nuestra denominación actual. Es por ello que nuestros desafíos,
                        hoy son nuestras metas. Déjate llevar por los senderos de la cordillera, del bosque, de las llanuras
                        de la costa peruana y vive el patrimonio natural, estético y cultural que guarda cada rincón de
                        nuestra rica geografía.</p>
                    </div>
                </div>
                <div class="valor">
                    <div><i class="fas fa-lightbulb"></i><strong>PIONEROS</strong><span class="icono"><i
                                class="fas fa-chevron-down"></i></span></div>
                    <div class="descripcion">Pioneras de aventura: Siempre buscamos nuevas formas de explorar y compartir
                        experiencias únicas.</div>
                </div>

                <div class="valor">
                    <div><i class="fas fa-map-marker-alt"></i><strong>GUÍAS</strong><span class="icono"><i
                                class="fas fa-chevron-down"></i></span></div>
                    <div class="descripcion">Los mejores guías locales que garantizan conocimiento y seguridad.</div>
                </div>

                <div class="valor">
                    <div><i class="fas fa-shield-alt"></i><strong>SEGURIDAD</strong><span class="icono"><i
                                class="fas fa-chevron-down"></i></span></div>
                    <div class="descripcion">Seguridad confiable: Nuestra prioridad es proteger cada paso de tu viaje.</div>
                </div>

                <div class="valor">
                    <div><i class="fas fa-cogs"></i><strong>SERVICIO</strong><span class="icono"><i
                                class="fas fa-chevron-down"></i></span></div>
                    <div class="descripcion">Servicio destacado: Atención personalizada y compromiso en cada detalle.</div>
                </div>

                <div class="valor">
                    <div><i class="fas fa-box"></i><strong>PRODUCTOS</strong><span class="icono"><i
                                class="fas fa-chevron-down"></i></span></div>
                    <div class="descripcion">Experiencias inspiradoras diseñadas para enriquecer tu aventura.</div>
                </div>

                <div class="valor">
                    <div><i class="fas fa-leaf"></i><strong>SOSTENIBILIDAD</strong><span class="icono"><i
                                class="fas fa-chevron-down"></i></span></div>
                    <div class="descripcion">Implementamos Sistemas de Gestión Ambiental para cuidar el entorno.</div>
                </div>
            </div>

            <h2 class="my-5">Lo que Dicen Nuestros Clientes</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="testimonial bg-white p-4 rounded shadow-sm">
                        <blockquote class="blockquote text-center">
                            <p class="mb-0">"¡Increíble experiencia! Gracias a esta agencia conocimos lugares que ni
                                imaginábamos. Los guías son muy amables y profesionales. Chan Chan fue impresionante."</p>
                            <footer class="blockquote-footer mt-2">María López, turista de Argentina</footer>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="https://wa.link/0037yw" target="_blank" class="btn btn-lg btn-danger">¡Contáctanos y planea tu
                    aventura!</a>
            </div>
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
