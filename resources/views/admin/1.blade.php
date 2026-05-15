@extends('adminlte::page')

@section('title', 'Configuración | White Label')

@section('content_header')
    <h1>⚙️ Configuración de Branding</h1>
@endsection

@section('content')
    <div class="container-fluid">

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <strong>❌ Errores encontrados:</strong>

                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card card-primary shadow-sm">

            <div class="card-header">
                <h3 class="card-title">
                    Personaliza tu marca
                </h3>
            </div>

            <form action="{{ route('configuracion.update') }}"
                method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- TABS --}}
                    <ul class="nav nav-tabs" id="configTabs" role="tablist">

                        <li class="nav-item">
                            <button class="nav-link active"
                                data-bs-toggle="tab"
                                data-bs-target="#identidad"
                                type="button">

                                🎨 Identidad Visual
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#colores"
                                type="button">

                                🌈 Colores
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#contacto"
                                type="button">

                                📞 Contacto y Redes
                            </button>
                        </li>

                        <li class="nav-item">
                            <button class="nav-link"
                                data-bs-toggle="tab"
                                data-bs-target="#seo"
                                type="button">

                                🔍 SEO y General
                            </button>
                        </li>

                    </ul>

                    <div class="tab-content mt-4">

                        {{-- ================================================= --}}
                        {{-- IDENTIDAD VISUAL --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane fade show active"
                            id="identidad">

                            {{-- EMPRESA --}}
                            <div class="row">

                                <div class="col-md-12">
                                    <h5 class="mb-3">
                                        📋 Información Empresa
                                    </h5>
                                </div>

                                <div class="col-md-6">

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Nombre Empresa
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            name="nombre_empresa"
                                            value="{{ old('nombre_empresa', $config->nombre_empresa) }}">
                                    </div>

                                </div>

                            </div>

                            <hr>

                            {{-- IMÁGENES --}}
                            <div class="row">

                                <div class="col-md-12">
                                    <h5 class="mb-4">
                                        🖼️ Logos e Imágenes
                                    </h5>
                                </div>

                                {{-- COMPONENTE SIMPLE --}}
                                @php
                                    $imagenes = [
                                        ['logo_url', 'Logo Principal'],
                                        ['logo_animation_url', 'Logo Animado'],
                                        ['logo_alt_url', 'Logo Alternativo'],
                                        ['favicon_url', 'Favicon'],
                                        ['background_login_url', 'Background Login'],
                                        ['hero_background_url', 'Hero Background'],
                                        ['background_mobile_url', 'Background Mobile'],
                                        ['nosotros_url', 'Imagen Nosotros'],
                                        ['certificacion_url', 'Imagen Certificación'],
                                        ['historia_url', 'Imagen Historia'],
                                        ['social_banner_url', 'Banner Social'],
                                        ['og_image_url', 'OG Image'],
                                    ];
                                @endphp

                                @foreach ($imagenes as [$campo, $label])
                                    <div class="col-md-3">

                                        <div class="card border">

                                            <div class="card-body">

                                                <label class="form-label fw-bold">
                                                    {{ $label }}
                                                </label>

                                                <div class="preview-box">

                                                    @if ($config->$campo)
                                                        <img src="{{ $config->$campo }}"
                                                            class="preview-image">
                                                    @else
                                                        <span class="text-muted">
                                                            Sin imagen
                                                        </span>
                                                    @endif

                                                </div>

                                                <input type="file"
                                                    class="form-control mt-2"
                                                    name="{{ $campo }}"
                                                    id="{{ $campo }}"
                                                    accept="image/*">

                                            </div>

                                        </div>

                                    </div>
                                @endforeach

                            </div>

                        </div>

                        {{-- ================================================= --}}
                        {{-- COLORES --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane fade"
                            id="colores">

                            <div class="row">

                                @php
                                    $colores = [
                                        ['color_primario', 'Color Primario'],
                                        ['color_secundario', 'Color Secundario'],
                                        ['color_terciario', 'Color Terciario'],
                                        ['color_acento', 'Color Acento'],
                                        ['color_texto_primario', 'Texto Primario'],
                                        ['color_texto_secundario', 'Texto Secundario'],
                                        ['color_fondo', 'Fondo Principal'],
                                        ['color_fondo_alterno', 'Fondo Alterno'],
                                    ];
                                @endphp

                                @foreach ($colores as [$campo, $label])

                                    <div class="col-md-6">

                                        <div class="mb-3">

                                            <label class="form-label">
                                                {{ $label }}
                                            </label>

                                            <div class="input-group">

                                                <input type="color"
                                                    class="form-control form-control-color"
                                                    id="{{ $campo }}"
                                                    name="{{ $campo }}"
                                                    value="{{ old($campo, $config->$campo) }}"
                                                    style="max-width: 90px;">

                                                <input type="text"
                                                    class="form-control"
                                                    id="{{ $campo }}_hex"
                                                    value="{{ old($campo, $config->$campo) }}"
                                                    readonly>

                                            </div>

                                        </div>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                        {{-- ================================================= --}}
                        {{-- CONTACTO --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane fade"
                            id="contacto">

                            <div class="row">

                                {{-- CONTACTO --}}
                                <div class="col-md-6">

                                    <h5 class="mb-3">
                                        📞 Información de Contacto
                                    </h5>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Email
                                        </label>

                                        <input type="email"
                                            class="form-control"
                                            name="email_contacto"
                                            value="{{ old('email_contacto', $config->email_contacto) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Teléfono Principal
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            name="telefono_principal"
                                            value="{{ old('telefono_principal', $config->telefono_principal) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">
                                            Dirección Física
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            name="direccion_fisica"
                                            value="{{ old('direccion_fisica', $config->direccion_fisica) }}">
                                    </div>

                                </div>

                                {{-- REDES --}}
                                <div class="col-md-6">

                                    <h5 class="mb-3">
                                        🌐 Redes Sociales
                                    </h5>

                                    @php
                                        $redes = [
                                            ['facebook_url', 'Facebook'],
                                            ['instagram_url', 'Instagram'],
                                            ['youtube_url', 'YouTube'],
                                            ['tiktok_url', 'TikTok'],
                                            ['whatsapp_numero', 'WhatsApp'],
                                        ];
                                    @endphp

                                    @foreach ($redes as [$campo, $label])

                                        <div class="mb-3">

                                            <label class="form-label">
                                                {{ $label }}
                                            </label>

                                            <input type="text"
                                                class="form-control"
                                                name="{{ $campo }}"
                                                value="{{ old($campo, $config->$campo) }}">

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        </div>

                        {{-- ================================================= --}}
                        {{-- SEO --}}
                        {{-- ================================================= --}}

                        <div class="tab-pane fade"
                            id="seo">

                            <div class="row">

                                <div class="col-md-6">

                                    <h5 class="mb-3">
                                        🔍 SEO y Metadatos
                                    </h5>

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Meta Título
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            name="meta_titulo"
                                            value="{{ old('meta_titulo', $config->meta_titulo) }}">

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Meta Descripción
                                        </label>

                                        <textarea class="form-control"
                                            rows="3"
                                            name="meta_descripcion">{{ old('meta_descripcion', $config->meta_descripcion) }}</textarea>

                                    </div>

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Meta Keywords
                                        </label>

                                        <textarea class="form-control"
                                            rows="3"
                                            name="meta_keywords">{{ old('meta_keywords', $config->meta_keywords) }}</textarea>

                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <h5 class="mb-3">
                                        ⚙️ Auditoría
                                    </h5>

                                    <div class="mb-3">

                                        <label class="form-label">
                                            Última modificación por
                                        </label>

                                        <input type="text"
                                            class="form-control"
                                            name="ultima_modificacion_por"
                                            value="{{ old('ultima_modificacion_por', $config->ultima_modificacion_por) }}">

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card-footer">

                    <button type="submit"
                        class="btn btn-primary">

                        💾 Guardar Cambios
                    </button>

                    <a href="{{ route('home') }}"
                        class="btn btn-secondary">

                        🌐 Ver Web
                    </a>

                </div>

            </form>

        </div>

    </div>
@endsection

@section('css')

    <style>

        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: #666;
            border: none;
            border-bottom: 3px solid transparent;
            transition: .3s;
            font-weight: 600;
        }

        .nav-tabs .nav-link.active {
            color: #dc030c;
            border-bottom-color: #dc030c;
            background: transparent;
        }

        .nav-tabs .nav-link:hover {
            color: #dc030c;
        }

        .preview-box {
            width: 100%;
            height: 120px;
            border: 2px dashed #ddd;
            border-radius: 10px;
            background: #fafafa;

            display: flex;
            align-items: center;
            justify-content: center;

            overflow: hidden;
        }

        .preview-image {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .card {
            border-radius: 12px;
        }

        .form-control-color {
            cursor: pointer;
        }

    </style>

@endsection

@section('js')

<script>

document.addEventListener('DOMContentLoaded', function () {

    // =========================
    // SINCRONIZAR COLORES HEX
    // =========================

    document.querySelectorAll('input[type="color"]').forEach(input => {

        const hex = document.getElementById(input.id + '_hex');

        if (hex) {

            input.addEventListener('input', function () {
                hex.value = this.value;
            });

        }

    });

    // =========================
    // PREVIEW IMAGENES
    // =========================

    document.querySelectorAll('input[type="file"]').forEach(input => {

        input.addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (!file) return;

            const reader = new FileReader();

            reader.onload = function (event) {

                const previewBox = input.closest('.card-body')
                    .querySelector('.preview-box');

                previewBox.innerHTML = `
                    <img src="${event.target.result}"
                        class="preview-image">
                `;

            };

            reader.readAsDataURL(file);

        });

    });

});

</script>

@endsection