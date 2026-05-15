<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar funciones helpers globales para Blade
        Blade::directive('brandingImage', function ($expression) {
            return "<?php echo brandingImage({$expression}); ?>";
        });
    try {
            $branding = Configuracion::obtener();

            if ($branding) {
                // 2. Sobrescribimos el Logo del Sidebar
                Config::set('adminlte.logo', "<b>{$branding->nombre_empresa}</b>");
                
                if ($branding->logo_url) {
                    Config::set('adminlte.logo_img', asset($branding->logo_url));
                }

                // 3. Sobrescribimos el Preloader (Logo de carga)
                if ($branding->logo_animation_url) {
                    Config::set('adminlte.preloader.img.path', asset($branding->logo_animation_url));
                }
                
                // 4. Extra: También puedes cambiar el nombre de la pestaña del navegador
                Config::set('adminlte.title', $branding->nombre_empresa);
            }
        } catch (\Exception $e) {
            // Si hay error (ej. durante la migración), no hacemos nada para no romper la app
        }

    }

    
}

/**
 * Helper: Obtener imagen de branding con fallback a public/imagenes
 * 
 * Uso en Blade:
 * @brandingImage('logo_url')
 * 
 * @param string $campo Campo de la configuración
 * @param string $fallback Imagen por defecto en public/
 * @return string URL de la imagen
 */
function brandingImage($campo, $fallback = null)
{
    $config = \App\Models\Configuracion::obtener();
    $imagen = $config->{$campo} ?? null;

    // Si existe imagen en storage, usarla
    if ($imagen && \Illuminate\Support\Facades\Storage::disk('public')->exists(str_replace('/storage/', '', $imagen))) {
        return asset($imagen);
    }

    // Si hay fallback, usarlo
    if ($fallback) {
        return asset($fallback);
    }

    // Mapear campos a imágenes por defecto
    $mapeo = [
        'logo_url' => 'imagenes/logo.png',
        'logo_animation_url' => 'imagenes/logo_animation.png',
        'logo_alt_url' => 'imagenes/logo.png',
        'favicon_url' => 'favicon.ico',
        'hero_background_url' => 'imagenes/hero-background.jpg',
        'social_banner_url' => 'imagenes/social-banner.jpg',
        'og_image_url' => 'imagenes/og-image.jpg',
    ];

    return asset($mapeo[$campo] ?? 'imagenes/logo.png');
}

/**
 * Helper: Generar bloque CSS con variables de branding
 * 
 * @return string HTML con <style> tag
 */
function brandingCss()
{
    $config = \App\Models\Configuracion::obtener();

    return <<<CSS
    <style>
        :root {
            /* Colores Principales */
            --color-primario: {$config->color_primario};
            --color-secundario: {$config->color_secundario};
            --color-terciario: {$config->color_terciario};
            --color-acento: {$config->color_acento};
            
            /* Colores de Texto y Fondo */
            --color-texto-primario: {$config->color_texto_primario};
            --color-texto-secundario: {$config->color_texto_secundario};
            --color-fondo: {$config->color_fondo};
            --color-fondo-alterno: {$config->color_fondo_alterno};
        
            
            /* Alias para compatibilidad (legacy naming) */
            --primary-red: {$config->color_primario};
            --primary-gold: {$config->color_secundario};
            --exp-red: {$config->color_primario};
        }
    </style>
    CSS;
}

