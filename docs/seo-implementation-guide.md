{{-- 
    GUÍA DE IMPLEMENTACIÓN SEO PARA AYNIFOREST
    ==============================================
    
    Este archivo documenta cómo implementar correctamente el SEO técnico
    en tu proyecto Laravel de reservas turísticas.
    
    ARCHIVO: docs/seo-implementation-guide.md
--}}

# Guía Completa de Implementación SEO - Ayniforest

## 📋 Tabla de Contenidos

1. [Robots.txt](#robotstxt)
2. [Layout Principal](#layout-principal)
3. [Vistas de Paquetes](#vistas-de-paquetes)
4. [Exclusión de Admin](#exclusión-del-admin)
5. [Sitemap XML](#sitemap-xml)
6. [Schema JSON-LD](#schema-json-ld)
7. [Checklist de Implementación](#checklist)

---

## 📄 Robots.txt

**Ubicación:** `public/robots.txt` ✅ YA CONFIGURADO

El archivo ya está optimizado con:
- ✅ Permite indexación de web pública
- ✅ Bloquea `/admin/*` completamente
- ✅ Bloquea `/login`, `/register` y rutas de autenticación
- ✅ Bloquea `/checkout`, `/mercadopago*`, `/payment*`
- ✅ Bloquea `/api/` y rutas internas
- ✅ Incluye referencia a `sitemap.xml`
- ✅ Política de crawling: 1 segundo de delay

### Verificar en Google Search Console:
1. Ve a: https://search.google.com/search-console
2. Añade tu sitio: https://ayniforestperu.com/
3. En "Settings" → "Crawl Stats" verifica que se respeta el robots.txt

---

## 🏗️ Layout Principal (layouts/app.blade.php)

**Estado:** ✅ ACTUALIZADO CON ESTRUCTURA COMPLETA

### Características Implementadas:

#### 1. Meta Tags Básicos Dinámicos
```blade
<meta name="robots" content="@yield('meta_robots', 'index, follow')">
<title>@yield('title', $defaultTitle)</title>
<meta name="description" content="@yield('meta_description', $defaultDescription)">
```

#### 2. Open Graph Completo
```blade
<meta property="og:title" content="@yield('og_title', $defaultTitle)">
<meta property="og:description" content="@yield('og_description', $defaultDescription)">
<meta property="og:image" content="@yield('og_image', $defaultImage)">
<meta property="og:url" content="@yield('og_url', $canonicalUrl)">
<meta property="og:type" content="@yield('og_type', 'website')">
<meta property="og:locale" content="es_PE">
```

#### 3. Twitter Cards
```blade
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="@yield('twitter_title', $defaultTitle)">
<meta name="twitter:description" content="@yield('twitter_description', $defaultDescription)">
```

#### 4. URL Canónica Dinámica
```blade
<link rel="canonical" href="@yield('canonical_url', $canonicalUrl)">
```

---

## 🎯 Vistas de Paquetes (Estructura Correcta)

**ARCHIVO DE EJEMPLO:** `resources/views/paguinas/paquete-seo-ejemplo.blade.php`

Este archivo muestra cómo estructurar correctamente una página de detalle de tour/paquete.

### Estructura SEO de Intención Comercial:

#### ✅ Title Tag (Palabra Clave Principal)
```blade
@section('title')
    {{ $ruta->nombre_ruta }} | Tours desde Trujillo - Ayniforest
@endsection
```
**Fórmula:** `[Destino] | Tours desde [Ciudad] - [Marca]`

#### ✅ Meta Description (120-160 caracteres)
```blade
@section('meta_description')
    Descubre {{ $ruta->nombre_ruta }}. Tour @strtolower($ruta->tipo) 
    desde Trujillo con {{ $ruta->duracion_horas ?? '8' }} horas de aventura. 
    Desde S/ {{ $ruta->precio_actual }}.
@endsection
```

#### ✅ Estructura HTML: UN único H1
```blade
<h1 class="display-4 fw-bold">
    {{ $ruta->nombre_ruta }}: Tour {{ strtolower($ruta->tipo) }} desde Trujillo
</h1>
```
**Regla:** Solo 1 H1 por página con la palabra clave principal

#### ✅ H2 para Secciones Principales
```blade
<h2>Descripción del Tour</h2>
<h2>Itinerario Detallado</h2>
<h2>¿Qué incluye este tour?</h2>
<h2>Tours Relacionados en La Libertad</h2>
```

#### ✅ H3 para Subsecciones
```blade
<h3>🚩 {{ $detalle->hora }} - {{ $detalle->lugar->nombre_lugar }}</h3>
```

#### ✅ Open Graph Optimizado para Redes
```blade
@section('og_title')
    {{ $ruta->nombre_ruta }} | Agencia Ayniforest
@endsection

@section('og_description')
    Tour {{ strtolower($ruta->tipo) }} a {{ $ruta->nombre_ruta }}. 
    Vive una experiencia única en La Libertad. Desde S/ {{ $ruta->precio_actual }}.
@endsection

@section('og_image')
    {{ $ruta->imagenes->first() ? asset($ruta->imagenes->first()->url_imagen) : asset('imagenes/og-image.jpg') }}
@endsection
```

#### ✅ Schema JSON-LD (Rich Snippets)
```blade
<script type="application/ld+json">
{
    "@context": "https://schema.org/",
    "@type": "TouristAttraction",
    "name": "{{ $ruta->nombre_ruta }}",
    "description": "{{ strip_tags($ruta->descripcion_general) }}",
    "image": "{{ asset(...) }}",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Trujillo",
        "addressRegion": "La Libertad",
        "addressCountry": "PE"
    },
    "offers": {
        "@type": "Offer",
        "priceCurrency": "PEN",
        "price": "{{ $ruta->precio_actual }}"
    }
}
</script>
```

---

## 🔒 Exclusión del Panel Administrativo

### Opción 1: Layout Específico para Admin (RECOMENDADO)

**ARCHIVO:** `resources/views/layouts/admin-base.blade.php` ✅ CREADO

Inyecta automáticamente `<meta name="robots" content="noindex, nofollow">` en todas las vistas admin.

#### Uso en vistas de admin:
```blade
@extends('layouts.admin-base')  {{-- En lugar de @extends('adminlte::page') --}}

@section('title', 'Configuración | White Label')

@section('content_header')
    <h1>⚙️ Configuración de Branding</h1>
@endsection

@section('content')
    {{-- Tu contenido aquí --}}
@endsection
```

### Opción 2: Middleware de Noindex

Crear `app/Http/Middleware/InjectNoindexMeta.php`:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InjectNoindexMeta
{
    public function handle(Request $request, Closure $next)
    {
        // Inyecta meta robots noindex en respuesta
        $response = $next($request);
        
        // Inyectar en el <head> si es respuesta HTML
        if ($response->headers->get('content-type') === 'text/html; charset=UTF-8') {
            $content = $response->getContent();
            $noindexMeta = '<meta name="robots" content="noindex, nofollow">' . "\n";
            $content = str_replace('</head>', $noindexMeta . '</head>', $content);
            $response->setContent($content);
        }
        
        return $response;
    }
}
```

Registrar en `app/Http/Kernel.php`:
```php
protected $routeMiddleware = [
    // ... otros middlewares
    'noindex' => \App\Http\Middleware\InjectNoindexMeta::class,
];
```

Usar en rutas (`routes/web.php`):
```php
Route::middleware(['noindex'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm']);
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::middleware(['auth'])->group(function () {
        Route::get('/admin', [AdminController::class, 'dashboard']);
        Route::get('/admin/*', [AdminController::class, 'handleAdmin']);
    });
});
```

---

## 🗺️ Sitemap XML

**CREAR:** `public/sitemap.xml`

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    {{-- PÁGINAS ESTÁTICAS --}}
    <url>
        <loc>https://ayniforestperu.com/</loc>
        <lastmod>2024-05-18</lastmod>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>
    
    <url>
        <loc>https://ayniforestperu.com/blog</loc>
        <lastmod>2024-05-18</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>https://ayniforestperu.com/renta-cars</loc>
        <lastmod>2024-05-18</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    {{-- RUTAS DINÁMICAS (Generar desde BD) --}}
    @foreach(\App\Models\Ruta::where('activo', 1)->get() as $ruta)
    <url>
        <loc>{{ route('rutas.descripcion', ['id_ruta' => $ruta->id_ruta]) }}</loc>
        <lastmod>{{ $ruta->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>
    @endforeach

    {{-- RUTAS POR TIPO (Tours Diarios/Weekend) --}}
    <url>
        <loc>https://ayniforestperu.com/tours/diarios</loc>
        <lastmod>2024-05-18</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>

    <url>
        <loc>https://ayniforestperu.com/tours/weekend</loc>
        <lastmod>2024-05-18</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
</urlset>
```

**Generar Dinámicamente:** Usar paquete `spatie/laravel-sitemap`:
```bash
composer require spatie/laravel-sitemap
```

---

## 📊 Schema JSON-LD

Tipos de Schema útiles para agencia de viajes:

### 1. Organization (Página Principal)
```blade
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Ayniforest",
    "url": "https://ayniforestperu.com",
    "logo": "https://ayniforestperu.com/imagenes/logo.webp",
    "description": "Agencia de viajes y turismo en Trujillo, La Libertad",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "[Tu dirección]",
        "addressLocality": "Trujillo",
        "addressRegion": "La Libertad",
        "postalCode": "[CP]",
        "addressCountry": "PE"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "Customer Service",
        "telephone": "+51-933-329-650",
        "email": "{{ $branding->email_contacto }}"
    },
    "sameAs": [
        "https://facebook.com/{{ $branding->facebook_url }}",
        "https://instagram.com/{{ $branding->instagram_url }}"
    ]
}
</script>
```

### 2. LocalBusiness (Información Local)
```blade
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Ayniforest",
    "image": "https://ayniforestperu.com/imagenes/logo.webp",
    "description": "Tours y paquetes turísticos en La Libertad",
    "telephone": "+51-933-329-650",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "[Dirección física]",
        "addressLocality": "Trujillo",
        "addressRegion": "La Libertad",
        "postalCode": "[CP]",
        "addressCountry": "PE"
    },
    "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
        "opens": "06:00",
        "closes": "22:00"
    },
    "aggregateRating": {
        "@type": "AggregateRating",
        "ratingValue": "4.8",
        "reviewCount": "150"
    }
}
</script>
```

### 3. BreadcrumbList (Navegación)
```blade
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Inicio",
            "item": "https://ayniforestperu.com/"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Tours Diarios",
            "item": "https://ayniforestperu.com/tours/diarios"
        },
        {
            "@type": "ListItem",
            "position": 3,
            "name": "{{ $ruta->nombre_ruta }}",
            "item": "{{ route('rutas.descripcion', ['id_ruta' => $ruta->id_ruta]) }}"
        }
    ]
}
</script>
```

---

## ✅ Checklist de Implementación

### Fase 1: Configuración Base
- [x] ✅ Robots.txt optimizado
- [x] ✅ Layout principal con meta tags dinámicos
- [x] ✅ Meta robots noindex en admin
- [ ] Crear sitemap.xml dinámico

### Fase 2: Vistas de Contenido
- [ ] Aplicar estructura de paquete-seo-ejemplo.blade.php a descripcionruta.blade.php
- [ ] Actualizar home.blade.php con H1 y H2 correctos
- [ ] Crear página de blog/nosotros optimizada
- [ ] Implementar páginas de categorías de tours (Diarios/Weekend)

### Fase 3: Schema JSON-LD
- [ ] Schema Organization en página principal
- [ ] Schema LocalBusiness en página principal
- [ ] Schema TouristAttraction en cada página de tour
- [ ] Schema BreadcrumbList en página de tours

### Fase 4: Verificación y Monitoreo
- [ ] Enviar sitemap a Google Search Console
- [ ] Verificar robots.txt en Google Search Console
- [ ] Usar Google Mobile-Friendly Test
- [ ] Usar Google Rich Results Test
- [ ] Verificar en Lighthouse (Performance > 90)
- [ ] Monitorear en Google Analytics 4

### Fase 5: Optimizaciones Adicionales
- [ ] Implementar Core Web Vitals
- [ ] Optimizar imágenes (WebP, lazy loading)
- [ ] Minificar CSS/JS
- [ ] Implementar CDN
- [ ] Cache HTTP headers
- [ ] Preload de fuentes

---

## 📌 Próximos Pasos Recomendados

1. **INMEDIATO:**
   - Actualizar `resources/views/paguinas/descripcionruta.blade.php` usando el ejemplo
   - Cambiar `@extends('adminlte::page')` por `@extends('layouts.admin-base')` en todas las vistas admin
   - Crear sitemap.xml dinámico

2. **CORTO PLAZO (1-2 semanas):**
   - Implementar schema JSON-LD en todas las páginas
   - Configurar Google Search Console
   - Monitorear indexación

3. **MEDIANO PLAZO (1 mes):**
   - A/B testing de títulos y descripciones
   - Mejorar velocidad de carga
   - Crear contenido de blog SEO-optimizado

---

## 🔗 Recursos Útiles

- [Google Search Central - SEO Starter Guide](https://developers.google.com/search/docs)
- [Schema.org Documentation](https://schema.org/)
- [Open Graph Protocol](https://ogp.me/)
- [Google Mobile-Friendly Test](https://search.google.com/test/mobile-friendly)
- [Google Rich Results Test](https://search.google.com/test/rich-results)

---

**Última actualización:** 18 de Mayo de 2024
**Versión:** 1.0
**Dominio:** https://ayniforestperu.com
