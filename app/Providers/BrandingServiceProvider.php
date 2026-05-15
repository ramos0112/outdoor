<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Configuracion;
use Illuminate\Support\Facades\Schema;

/**
 * BrandingServiceProvider
 * 
 * Proporciona la configuración de branding a todas las vistas de Blade automáticamente.
 * 
 * Hace disponible la variable $branding en todas las vistas sin necesidad de
 * pasar datos desde los controladores. Esto permite dinamizar:
 * - Logos (logo_url, logo_animation_url)

 */
class BrandingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     * 
     * Comparte la configuración de branding a todas las vistas cuando se registra el provider.
     */
    public function boot(): void
    {
        // 1. Verificación de seguridad: No ejecutar si estamos en la consola (migraciones/seeders)
        if ($this->app->runningInConsole()) {
            return;
        }

        try {
            // 2. Solo intentar cargar si la tabla ya existe físicamente en la BD
            if (Schema::hasTable('configuraciones')) {
                $branding = Configuracion::obtener();
                
                // Compartir la configuración en todas las vistas
                View::share('branding', $branding);
                View::share('config', $branding);
            }
        } catch (\Exception $e) {
            // 3. Fallback silencioso: Si algo falla, la app no se detiene
            // Podrías loguear el error si fuera necesario: \Log::error($e->getMessage());
        }
    }
}
