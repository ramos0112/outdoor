
@extends('adminlte::page')

@section('title', 'Configuración | White Label')

@section('content_header')
    <h1>⚙️ Configuración de Branding</h1>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Mensajes de éxito/error -->
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>❌ Errores de validación:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Personaliza tu marca</h3>
                    </div>
                    <form action="{{ route('configuracion.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Pestañas -->
                        <div class="card-body">
                            <ul class="nav nav-tabs" id="configTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="identidad-tab" data-bs-toggle="tab"
                                        data-bs-target="#identidad" type="button" role="tab">
                                        🎨 Identidad Visual
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="colores-tab" data-bs-toggle="tab" data-bs-target="#colores"
                                        type="button" role="tab">
                                        🌈 Colores
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="contacto-tab" data-bs-toggle="tab"
                                        data-bs-target="#contacto" type="button" role="tab">
                                        📞 Contacto y Redes
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo"
                                        type="button" role="tab">
                                        🔍 SEO y General
                                    </button>
                                </li>
                            </ul>
                            <!-- TAB 1: IDENTIDAD VISUAL -->
                            <div class="tab-content mt-3" id="configTabsContent">
                                <div class="tab-pane fade show active" id="identidad" role="tabpanel">
                                    <div class="row">
                                        <!-- Información de Empresa -->
                                        <div class="col-md-6">
                                            <h5 class="mb-3">📋 Información de Empresa</h5>
                                            <div class="mb-3">
                                                <label for="nombre_empresa" class="form-label">Nombre de Empresa *</label>
                                                <input type="text"
                                                    class="form-control @error('nombre_empresa') is-invalid @enderror"
                                                    id="nombre_empresa" name="nombre_empresa"
                                                    value="{{ old('nombre_empresa', $config->nombre_empresa) }}" required>
                                                @error('nombre_empresa')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <!-- Logos e Imágenes -->
                                    <h5 class="mb-3">🖼️ Logos e Imágenes</h5>
                                    <div class="row">
                                        <!-- Logo Principal -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="logo_url" class="form-label">Logo Principal</label>
                                                <div class="mb-2 p-2 border border-2 border-dashed rounded"
                                                    style="background-color: #f8f9fa; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                                                    @if ($config->logo_url)
                                                        <img src="{{ $config->logo_url }}" alt="Logo"
                                                            style="max-height: 80px; max-width: 100%;">
                                                    @else
                                                        <span class="text-muted">Sin imagen</span>
                                                    @endif
                                                </div>
                                                <input type="file"
                                                    class="form-control @error('logo_url') is-invalid @enderror"
                                                    id="logo_url" name="logo_url" accept="image/*">
                                                <small class="text-muted d-block mt-1">Máx. 5 MB | PNG, JPG, GIF</small>
                                                @error('logo_url')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <!-- Logo Animado -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="logo_animation_url" class="form-label">Logo Animado</label>
                                                <div class="mb-2 p-2 border border-2 border-dashed rounded"
                                                    style="background-color: #f8f9fa; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                                                    @if ($config->logo_animation_url)
                                                        <img src="{{ $config->logo_animation_url }}" alt="Logo Animado"
                                                            style="max-height: 80px; max-width: 100%;">
                                                    @else
                                                        <span class="text-muted">Sin imagen</span>
                                                    @endif
                                                </div>
                                                <input type="file"
                                                    class="form-control @error('logo_animation_url') is-invalid @enderror"
                                                    id="logo_animation_url" name="logo_animation_url" accept="image/*">
                                                <small class="text-muted d-block mt-1">Máx. 5 MB</small>
                                            </div>
                                        </div>
                                        <!-- Logo Alternativo -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="logo_alt_url" class="form-label">Logo Alternativo</label>
                                                <div class="mb-2 p-2 border border-2 border-dashed rounded"
                                                    style="background-color: #f8f9fa; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                                                    @if ($config->logo_alt_url)
                                                        <img src="{{ $config->logo_alt_url }}" alt="Logo Alt"
                                                            style="max-height: 80px; max-width: 100%;">
                                                    @else
                                                        <span class="text-muted">Sin imagen</span>
                                                    @endif
                                                </div>
                                                <input type="file"
                                                    class="form-control @error('logo_alt_url') is-invalid @enderror"
                                                    id="logo_alt_url" name="logo_alt_url" accept="image/*">
                                                <small class="text-muted d-block mt-1">Máx. 5 MB</small>
                                            </div>
                                        </div>
                                        <!-- Favicon -->
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label for="favicon_url" class="form-label">Favicon</label>
                                                <div class="mb-2 p-2 border border-2 border-dashed rounded"
                                                    style="background-color: #f8f9fa; min-height: 100px; display: flex; align-items: center; justify-content: center;">
                                                    @if ($config->favicon_url)
                                                        <img src="{{ $config->favicon_url }}" alt="Favicon"
                                                            style="max-height: 64px; max-width: 100%;">
                                                    @else
                                                        <span class="text-muted">Sin imagen</span>
                                                    @endif
                                                </div>
                                                <input type="file"
                                                    class="form-control @error('favicon_url') is-invalid @enderror"
                                                    id="favicon_url" name="favicon_url" accept="image/*">
                                                <small class="text-muted d-block mt-1">Máx. 1 MB | 64x64px</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <!-- Fondo Hero -->
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label for="hero_background_url" class="form-label">Fondo Hero</label>
                                                <div class="mb-2 p-2 border border-2 border-dashed rounded"
                                                    style="background-color: #f8f9fa; min-height: 80px; display: flex; align-items: center; justify-content: center;">
                                                    @if ($config->hero_background_url)
                                                        <img src="{{ $config->hero_background_url }}" alt="Fondo Hero"
                                                            style="max-height: 60px; max-width: 100%;">
                                                    @else
                                                        <span class="text-muted">Sin imagen</span>
                                                    @endif
                                                </div>
                                                <input type="file"
                                                    class="form-control @error('hero_background_url') is-invalid @enderror"
                                                    id="hero_background_url" name="hero_background_url" accept="image/*">
                                                <small class="text-muted d-block mt-1">Máx. 10 MB | 1920x1080px</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- TAB 2: COLORES -->
                                <div class="tab-pane fade" id="colores" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="mb-3">🎨 Paleta de Colores Primaria</h5>
                                            <div class="mb-3">
                                                <label for="color_primario" class="form-label">Color Primario</label>
                                                <div class="input-group">
                                                    <input type="color"
                                                        class="form-control form-control-color @error('color_primario') is-invalid @enderror"
                                                        id="color_primario" name="color_primario"
                                                        value="{{ old('color_primario', $config->color_primario) }}"
                                                        style="max-width: 100px;">
                                                    <input type="text" class="form-control" id="color_primario_hex"
                                                        value="{{ old('color_primario', $config->color_primario) }}"
                                                        readonly>
                                                </div>
                                                <small class="text-muted d-block mt-1">Color principal de la marca</small>
                                                @error('color_primario')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="color_secundario" class="form-label">Color Secundario</label>
                                                <div class="input-group">
                                                    <input type="color"
                                                        class="form-control form-control-color @error('color_secundario') is-invalid @enderror"
                                                        id="color_secundario" name="color_secundario"
                                                        value="{{ old('color_secundario', $config->color_secundario) }}"
                                                        style="max-width: 100px;">
                                                    <input type="text" class="form-control" id="color_secundario_hex"
                                                        value="{{ old('color_secundario', $config->color_secundario) }}"
                                                        readonly>
                                                </div>
                                                <small class="text-muted d-block mt-1">Color complementario</small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="color_terciario" class="form-label">Color Terciario</label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color"
                                                        id="color_terciario" name="color_terciario"
                                                        value="{{ old('color_terciario', $config->color_terciario) }}"
                                                        style="max-width: 100px;">
                                                    <input type="text" class="form-control" id="color_terciario_hex"
                                                        value="{{ old('color_terciario', $config->color_terciario) }}"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="color_acento" class="form-label">Color de Acento</label>
                                                <div class="input-group">
                                                    <input type="color" class="form-control form-control-color"
                                                        id="color_acento" name="color_acento"
                                                        value="{{ old('color_acento', $config->color_acento) }}"
                                                        style="max-width: 100px;">
                                                    <input type="text" class="form-control" id="color_acento_hex"
                                                        value="{{ old('color_acento', $config->color_acento) }}" readonly>
                                                </div>
                                                <small class="text-muted d-block mt-1">Para botones y elementos
                                                    destacados</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- TAB 3: CONTACTO Y REDES -->
                                <div class="tab-pane fade" id="contacto" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="mb-3">📞 Información de Contacto</h5>
                                            <div class="mb-3">
                                                <label for="email_contacto" class="form-label">Email de Contacto</label>
                                                <input type="email"
                                                    class="form-control @error('email_contacto') is-invalid @enderror"
                                                    id="email_contacto" name="email_contacto"
                                                    value="{{ old('email_contacto', $config->email_contacto) }}">
                                                @error('email_contacto')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="telefono_principal" class="form-label">Teléfono
                                                    Principal</label>
                                                <input type="text" class="form-control" id="telefono_principal"
                                                    name="telefono_principal" placeholder="+34 91 555 0123"
                                                    value="{{ old('telefono_principal', $config->telefono_principal) }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="direccion_fisica" class="form-label">Dirección Física</label>
                                                <input type="text" class="form-control" id="direccion_fisica"
                                                    name="direccion_fisica"
                                                    value="{{ old('direccion_fisica', $config->direccion_fisica) }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h5 class="mb-3">🌐 Redes Sociales</h5>
                                            <div class="mb-3">
                                                <label for="facebook_url" class="form-label">Facebook</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">f</span>
                                                    <input type="url" class="form-control" id="facebook_url"
                                                        name="facebook_url" placeholder="https://facebook.com/..."
                                                        value="{{ old('facebook_url', $config->facebook_url) }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="instagram_url" class="form-label">Instagram</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">📷</span>
                                                    <input type="url" class="form-control" id="instagram_url"
                                                        name="instagram_url" placeholder="https://instagram.com/..."
                                                        value="{{ old('instagram_url', $config->instagram_url) }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="youtube_url" class="form-label">YouTube</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">▶</span>
                                                    <input type="url" class="form-control" id="youtube_url"
                                                        name="youtube_url" placeholder="https://youtube.com/..."
                                                        value="{{ old('youtube_url', $config->youtube_url) }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tiktok_url" class="form-label">TikTok</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">♪</span>
                                                    <input type="url" class="form-control" id="tiktok_url"
                                                        name="tiktok_url" placeholder="https://tiktok.com/..."
                                                        value="{{ old('tiktok_url', $config->tiktok_url) }}">
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="whatsapp_numero" class="form-label">WhatsApp</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">💬</span>
                                                    <input type="text" class="form-control" id="whatsapp_numero"
                                                        name="whatsapp_numero" placeholder="+34 600 123 456"
                                                        value="{{ old('whatsapp_numero', $config->whatsapp_numero) }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- TAB 4: SEO Y GENERAL -->
                                <div class="tab-pane fade" id="seo" role="tabpanel">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5 class="mb-3">🔍 SEO y Metadatos</h5>
                                            <div class="mb-3">
                                                <label for="meta_titulo" class="form-label">Meta Título</label>
                                                <input type="text" class="form-control" id="meta_titulo"
                                                    name="meta_titulo"
                                                    value="{{ old('meta_titulo', $config->meta_titulo) }}"
                                                    maxlength="255">
                                                <small class="text-muted d-block mt-1">Máx. 60 caracteres (aparece en
                                                    buscadores)</small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="meta_descripcion" class="form-label">Meta Descripción</label>
                                                <textarea class="form-control" id="meta_descripcion" name="meta_descripcion" rows="2" maxlength="500">{{ old('meta_descripcion', $config->meta_descripcion) }}</textarea>
                                                <small class="text-muted d-block mt-1">Máx. 160 caracteres</small>
                                            </div>
                                            <div class="mb-3">
                                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                                <textarea class="form-control" id="meta_keywords" name="meta_keywords" rows="2">{{ old('meta_keywords', $config->meta_keywords) }}</textarea>
                                                <small class="text-muted d-block mt-1">Palabras clave separadas por
                                                    comas</small>
                                            </div>
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
    </div>
@endsection

@section('css')
    <style>
        .nav-tabs {
            border-bottom: 2px solid #dee2e6;
        }

        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            color: #495057;
            border-bottom-color: #dc030c;
        }

        .nav-tabs .nav-link.active {
            color: #dc030c;
            background-color: transparent;
            border-bottom-color: #dc030c;
        }

        .tab-pane {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .form-control-color {
            cursor: pointer;
            border: 1px solid #dee2e6;
        }

        .form-control-color:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 3, 12, 0.25);
            border-color: #dc030c;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
        }

        .card-footer {
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
        }

        h5 {
            font-weight: 600;
            color: #333;
        }
    </style>
@endsection

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sincronizar inputs de color con valores hexadecimales
            document.querySelectorAll('input[type="color"]').forEach(colorInput => {
                const hexInput = document.getElementById(colorInput.id + '_hex');

                // Sincronizar al cargar
                if (hexInput) {
                    hexInput.value = colorInput.value;

                    // Sincronizar al cambiar el color picker
                    colorInput.addEventListener('change', (e) => {
                        hexInput.value = e.target.value;
                    });

                    // Sincronizar en tiempo real mientras se está seleccionando
                    colorInput.addEventListener('input', (e) => {
                        hexInput.value = e.target.value;
                    });
                }
            });

            // Inicializar Bootstrap tabs manualmente si es necesario
            const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
            tabButtons.forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabPane = document.querySelector(this.getAttribute('data-bs-target'));
                    if (tabPane) {
                        // Remover clase active de todas las pestañas y panes
                        document.querySelectorAll('.nav-link').forEach(el => el.classList.remove(
                            'active'));
                        document.querySelectorAll('.tab-pane').forEach(el => el.classList.remove(
                            'show', 'active'));

                        // Agregar clase active a la pestaña y pane actual
                        this.classList.add('active');
                        tabPane.classList.add('show', 'active');
                    }
                });
            });
        });
    </script>
@endsection
