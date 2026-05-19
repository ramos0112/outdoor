@extends('layouts.admin-base')

@section('title', 'Configuración | White Label')

@section('content_header')
    <h1>⚙️ Configuración de Branding</h1>
@endsection

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button\" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card card-primary card-outline card-tabs">
            <div class="card-header p-0 pt-1 border-bottom-0">
                <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="tabs-general-tab" data-toggle="pill" href="#tabs-general"
                            role="tab">General y Contacto</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-logos-tab" data-toggle="pill" href="#tabs-logos" role="tab">Logos e
                            Imágenes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-colores-tab" data-toggle="pill" href="#tabs-colores"
                            role="tab">Colores y Estilo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="tabs-redes-tab" data-toggle="pill" href="#tabs-redes" role="tab">Redes
                            Sociales y SEO</a>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="tab-content" id="custom-tabs-three-tabContent">

                        <div class="tab-pane fade show active" id="tabs-general" role="tabpanel">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Nombre de la Empresa</label>
                                    <input type="text" name="nombre_empresa" class="form-control"
                                        value="{{ old('nombre_empresa', $config->nombre_empresa) }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Email de Contacto</label>
                                    <input type="email" name="email_contacto" class="form-control"
                                        value="{{ old('email_contacto', $config->email_contacto) }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Teléfono Principal</label>
                                    <input type="text" name="telefono_principal" class="form-control"
                                        value="{{ old('telefono_principal', $config->telefono_principal) }}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Dirección Física</label>
                                    <input type="text" name="direccion_fisica" class="form-control"
                                        value="{{ old('direccion_fisica', $config->direccion_fisica) }}">
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tabs-logos" role="tabpanel">
                            <div class="row">
                                @php
                                    $camposImagen = [
                                        'logo_url' => 'Logo Principal',
                                        'logo_animation_url' => 'Logo Animado',
                                        'favicon_url' => 'Favicon',
                                        'logo_alt_url' => 'Logo Alternativo',
                                        'background_login_url' => 'Fondo Login',
                                        'hero_background_url' => 'Fondo Hero (Inicio)',
                                        'background_mobile_url' => 'Fondo Móvil',
                                        'nosotros_url' => 'Imagen Nosotros',
                                        'certificacion_url' => 'Imagen Certificación',
                                        'historia_url' => 'Imagen Historia',
                                    ];
                                @endphp

                                @foreach ($camposImagen as $campo => $label)
                                    <div class="col-md-3 col-sm-6 mb-4 text-center">
                                        <label class="d-block">{{ $label }}</label>
                                        <img src="{{ $config->$campo ?? asset('imagenes/placeholder.png') }}"
                                            class="img-thumbnail mb-2 shadow-sm"
                                            style="height: 100px; object-fit: contain;">
                                        <div class="custom-file text-left">
                                            {{-- Se usa $campo en id y name para que cada input sea único --}}
                                            <input type="file"
                                                class="form-control form-control-sm @error($campo) is-invalid @enderror"
                                                id="{{ $campo }}" name="{{ $campo }}" accept="image/*">
                                            @error($campo)
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tabs-colores" role="tabpanel">
                            <div class="row">
                                @php
                                    $colores = [
                                        'color_primario' => 'Color Primario',
                                        'color_secundario' => 'Color Secundario',
                                        'color_terciario' => 'Color Terciario',
                                        'color_acento' => 'Color de Acento',
                                        'color_texto_primario' => 'Texto Principal',
                                        'color_texto_secundario' => 'Texto Secundario',
                                        'color_fondo' => 'Fondo Base',
                                        'color_fondo_alterno' => 'Fondo Alterno',
                                    ];
                                @endphp

                                @foreach ($colores as $name => $label)
                                    <div class="col-md-3 mb-3">
                                        <label>{{ $label }}</label>
                                        <div class="input-group">
                                            <input type="color" class="form-control form-control-color w-25"
                                                id="cp_{{ $name }}" value="{{ old($name, $config->$name) }}">
                                            <input type="text" name="{{ $name }}" id="tx_{{ $name }}"
                                                class="form-control" value="{{ old($name, $config->$name) }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tabs-redes" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6 border-right">
                                    <h5 class="text-primary mb-3">Redes Sociales</h5>
                                    <div class="form-group">
                                        <label>WhatsApp (Número con código de país)</label>
                                        <input type="text" name="whatsapp_numero" class="form-control"
                                            value="{{ old('whatsapp_numero', $config->whatsapp_numero) }}">
                                    </div>
                                    <div class="form-group"><label>Facebook URL</label><input type="url"
                                            name="facebook_url" class="form-control"
                                            value="{{ $config->facebook_url }}"></div>
                                    <div class="form-group"><label>Instagram URL</label><input type="url"
                                            name="instagram_url" class="form-control"
                                            value="{{ $config->instagram_url }}"></div>
                                    <div class="form-group"><label>TikTok URL</label><input type="url"
                                            name="tiktok_url" class="form-control" value="{{ $config->tiktok_url }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h5 class="text-primary mb-3">SEO y Metadatos</h5>
                                    <div class="form-group">
                                        <label>Meta Título</label>
                                        <input type="text" name="meta_titulo" class="form-control"
                                            value="{{ $config->meta_titulo }}">
                                    </div>
                                    <div class="form-group">
                                        <label>Meta Descripción</label>
                                        <textarea name="meta_descripcion" class="form-control" rows="3">{{ $config->meta_descripcion }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer del formulario -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            💾 Guardar Cambios
                        </button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">
                            Ver Web
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            // Sincronizar input color con input texto
            $('input[type="color"]').on('input', function() {
                let id = $(this).attr('id').replace('cp_', 'tx_');
                $('#' + id).val($(this).val());
            });

            $('input[type="text"]').on('input', function() {
                if ($(this).attr('id') && $(this).attr('id').startsWith('tx_')) {
                    let id = $(this).attr('id').replace('tx_', 'cp_');
                    if (/^#[0-9A-F]{6}$/i.test($(this).val())) {
                        $('#' + id).val($(this).val());
                    }
                }
            });

            // Mostrar nombre de archivo seleccionado en Bootstrap
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@endsection

