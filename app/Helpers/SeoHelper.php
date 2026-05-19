<?php

/**
 * app/Helpers/SeoHelper.php
 * 
 * Helper de funciones para SEO
 * 
 * INSTALACIÓN:
 * 1. Registrar en composer.json:
 *    "autoload": {
 *        "files": ["app/Helpers/SeoHelper.php"]
 *    }
 * 
 * 2. Ejecutar: composer dump-autoload
 * 
 * USO en vistas Blade:
 * {{ seoMeta('title', 'Mi Título SEO') }}
 * {!! seoSchemaOrganization() !!}
 * {!! seoSchemaTouristAttraction($ruta) !!}
 */

namespace App\Helpers;

class SeoHelper
{
    /**
     * Obtener valores por defecto de branding
     */
    public static function getBranding()
    {
        return \App\Models\Configuracion::obtener();
    }

    /**
     * Generar meta tag genérico
     * 
     * @param string $name Nombre del atributo
     * @param string $content Contenido del atributo
     * @return string HTML del meta tag
     */
    public static function seoMeta(string $name, string $content): string
    {
        return '<meta name="' . htmlspecialchars($name) . '" content="' . htmlspecialchars($content) . '">';
    }

    /**
     * Generar Schema JSON-LD para Organization
     * 
     * @return string JSON-LD
     */
    public static function seoSchemaOrganization(): string
    {
        $branding = self::getBranding();
        
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "Organization",
            "name" => $branding->nombre_empresa ?? "Ayniforest",
            "url" => config('app.url'),
            "logo" => $branding->og_image_url ?? asset('imagenes/logo.webp'),
            "description" => $branding->meta_descripcion ?? "Agencia de viajes en Trujillo, La Libertad",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $branding->direccion_fisica ?? "",
                "addressLocality" => "Trujillo",
                "addressRegion" => "La Libertad",
                "addressCountry" => "PE"
            ],
            "contactPoint" => [
                "@type" => "ContactPoint",
                "contactType" => "Customer Service",
                "telephone" => $branding->telefono_principal ?? "+51-933-329-650",
                "email" => $branding->email_contacto ?? "info@ayniforest.com"
            ]
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generar Schema JSON-LD para LocalBusiness
     * 
     * @return string JSON-LD
     */
    public static function seoSchemaLocalBusiness(): string
    {
        $branding = self::getBranding();
        
        $schema = [
            "@context" => "https://schema.org",
            "@type" => "LocalBusiness",
            "name" => $branding->nombre_empresa ?? "Ayniforest",
            "image" => $branding->og_image_url ?? asset('imagenes/logo.webp'),
            "description" => $branding->meta_descripcion ?? "Tours y paquetes turísticos en La Libertad",
            "telephone" => $branding->telefono_principal ?? "+51-933-329-650",
            "address" => [
                "@type" => "PostalAddress",
                "streetAddress" => $branding->direccion_fisica ?? "",
                "addressLocality" => "Trujillo",
                "addressRegion" => "La Libertad",
                "postalCode" => "",
                "addressCountry" => "PE"
            ],
            "openingHoursSpecification" => [
                "@type" => "OpeningHoursSpecification",
                "dayOfWeek" => ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                "opens" => "06:00",
                "closes" => "22:00"
            ]
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generar Schema JSON-LD para TouristAttraction (Tours/Rutas)
     * 
     * @param object $ruta Objeto de la ruta
     * @return string JSON-LD
     */
    public static function seoSchemaTouristAttraction($ruta): string
    {
        $schema = [
            "@context" => "https://schema.org/",
            "@type" => "TouristAttraction",
            "name" => $ruta->nombre_ruta ?? "Tour",
            "description" => strip_tags($ruta->descripcion_general ?? ""),
            "image" => $ruta->imagenes->first() ? asset($ruta->imagenes->first()->url_imagen) : asset('imagenes/og-image.jpg'),
            "address" => [
                "@type" => "PostalAddress",
                "addressLocality" => "Trujillo",
                "addressRegion" => "La Libertad",
                "addressCountry" => "PE"
            ],
            "offers" => [
                "@type" => "Offer",
                "priceCurrency" => "PEN",
                "price" => $ruta->precio_actual ?? 0,
                "availability" => "https://schema.org/InStock"
            ],
            "aggregateRating" => [
                "@type" => "AggregateRating",
                "ratingValue" => "4.8",
                "reviewCount" => "100"
            ]
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generar Schema JSON-LD para BreadcrumbList (Navegación)
     * 
     * @param array $breadcrumbs Array con estructura: [['name' => 'Inicio', 'url' => '/'], ...]
     * @return string JSON-LD
     */
    public static function seoSchemaBreadcrumbs(array $breadcrumbs): string
    {
        $items = [];
        foreach ($breadcrumbs as $position => $crumb) {
            $items[] = [
                "@type" => "ListItem",
                "position" => $position + 1,
                "name" => $crumb['name'] ?? "",
                "item" => $crumb['url'] ?? ""
            ];
        }

        $schema = [
            "@context" => "https://schema.org",
            "@type" => "BreadcrumbList",
            "itemListElement" => $items
        ];

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Generar URL canónica limpia
     * 
     * @return string URL
     */
    public static function canonicalUrl(): string
    {
        return url()->current();
    }

    /**
     * Limpiar texto para meta description (max 160 chars)
     * 
     * @param string $text Texto a limpiar
     * @param int $maxLength Longitud máxima
     * @return string
     */
    public static function cleanMetaDescription(string $text, int $maxLength = 160): string
    {
        // Remover HTML tags
        $text = strip_tags($text);
        
        // Remover espacios múltiples
        $text = preg_replace('/\s+/', ' ', $text);
        
        // Truncar
        if (strlen($text) > $maxLength) {
            $text = substr($text, 0, $maxLength - 3) . '...';
        }
        
        return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Verificar si está en ruta que debe tener noindex
     * 
     * @return bool
     */
    public static function shouldNoindex(): bool
    {
        $noindexRoutes = [
            'login',
            'register',
            'password.*',
            'admin.*',
            'dashboard',
            'panel',
            'checkout',
            'mercadopago*',
            'payment*'
        ];

        foreach ($noindexRoutes as $route) {
            if (\Route::currentRouteNamed($route) || request()->is($route)) {
                return true;
            }
        }

        return false;
    }
}

/**
 * Helper functions globales para usar en Blade
 */

if (!function_exists('seoMeta')) {
    function seoMeta(string $name, string $content): string
    {
        return \App\Helpers\SeoHelper::seoMeta($name, $content);
    }
}

if (!function_exists('seoSchemaOrganization')) {
    function seoSchemaOrganization(): string
    {
        return \App\Helpers\SeoHelper::seoSchemaOrganization();
    }
}

if (!function_exists('seoSchemaLocalBusiness')) {
    function seoSchemaLocalBusiness(): string
    {
        return \App\Helpers\SeoHelper::seoSchemaLocalBusiness();
    }
}

if (!function_exists('seoSchemaTouristAttraction')) {
    function seoSchemaTouristAttraction($ruta): string
    {
        return \App\Helpers\SeoHelper::seoSchemaTouristAttraction($ruta);
    }
}

if (!function_exists('seoSchemaBreadcrumbs')) {
    function seoSchemaBreadcrumbs(array $breadcrumbs): string
    {
        return \App\Helpers\SeoHelper::seoSchemaBreadcrumbs($breadcrumbs);
    }
}

if (!function_exists('canonicalUrl')) {
    function canonicalUrl(): string
    {
        return \App\Helpers\SeoHelper::canonicalUrl();
    }
}

if (!function_exists('cleanMetaDescription')) {
    function cleanMetaDescription(string $text, int $maxLength = 160): string
    {
        return \App\Helpers\SeoHelper::cleanMetaDescription($text, $maxLength);
    }
}

if (!function_exists('shouldNoindex')) {
    function shouldNoindex(): bool
    {
        return \App\Helpers\SeoHelper::shouldNoindex();
    }
}
