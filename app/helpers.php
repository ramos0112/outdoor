<?php

use App\Models\Configuracion;

if (!function_exists('brandingCss')) {
function brandingCss() {
    $config = \App\Models\Configuracion::obtener();
    
    return "
    <style>
        :root {
            --color-primario: {$config->color_primario};
            --color-secundario: {$config->color_secundario};
            --color-terciario: {$config->color_terciario};
            --color-acento: {$config->color_acento};
            --color-texto-principal: {$config->color_texto_primario};
            --color-texto-secundario: {$config->color_texto_secundario};
            --color-fondo: {$config->color_fondo};
        }

        /* --- NAVEGACIÓN (NAVBAR) --- */
        /* Color base de los links (blanco o gris claro por defecto) */
        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            transition: all 0.3s ease;
        }

        /* Al pasar el mouse: Color Texto Principal */
        .nav-link:hover {
            color: var(--color-texto-principal) !important;
        }

        /* Link activo: Color Secundario (para resaltar dónde está) */
        .nav-link.active {
            color: var(--color-secundario) !important;
        }
    
        /* --- JERARQUÍA DE TEXTOS GLOBALES --- */
        
        /* Títulos principales (H1, H2) -> Color Primario */
        h1, h2, .adventure-h2, .container h2, .valores-container h2 {
            color: var(--color-texto-principal) !important;
            font-family: 'Poppins', sans-serif;
        }

        /* Subtítulos o títulos de tarjetas -> Color Texto Principal */
        h3, h4, h5, h6, .package-title, .cta-container h3 {
            color: var(--color-texto-principal) !important;
        }

        /* Párrafos, descripciones y textos largos -> Color Texto Secundario */
        p, .description, .valor p, .summary-card p, .footer p {
            color: var(--color-texto-secundario) !important;
        }

        /* --- DETALLES DINÁMICOS EN VISTAS --- */

        /* Iconos en Blog/Nosotros -> Color Secundario */
        .icono i, .valor i, .fa-phone, .fa-envelope, .fa-map-marker-alt {
            color: var(--color-secundario) !important;
        }

        /* Precios o resaltados importantes -> Color Primario */
        .price-value, .fs-2, .text-danger {
            color: var(--color-primario) !important;
        }

        /* --- BOTONES (Reafirmando el estilo) --- */
        .btn-danger, .package-btn {
            background-color: var(--color-primario) !important;
            border-color: var(--color-primario) !important;
            color: #ffffff !important; /* Texto del botón siempre blanco para legibilidad */
        }

        .btn-danger:hover {
            background-color: var(--color-secundario) !important;
            border-color: var(--color-secundario) !important;
        }

    </style>";
}
}

if (!function_exists('brandingImage')) {
    function brandingImage($campo, $default = 'imagenes/placeholder.png') {
        $config = Configuracion::obtener();
        return $config->$campo ? asset($config->$campo) : asset($default);
    }
}