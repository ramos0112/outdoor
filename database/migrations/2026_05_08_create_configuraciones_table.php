<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * MIGRACIÓN: Tabla de Configuraciones Dinámicas
     * 
     * Propósito: Centralizar todos los datos hardcoded de branding, colores,
     *            logos, textos y redes sociales encontrados en frontend.
     * 
     * Hallazgos previos (frontend.md):
     * - 27 elementos hardcoded de "Outdoor Expeditions"
     * - Logos: logo.png, logo_animation.png, autdoor.png
     * - Color primario: #dc030c (rojo expediciones)
     * - Redes sociales en layouts/app.blade.php
     * - Textos en múltiples vistas
     * 
     * Solución: Una tabla centralizada que puede ser por empresa (multi-tenant)
     */
    
    public function up(): void
    {
        Schema::create('configuraciones', function (Blueprint $table) {
            $table->id('id_configuracion'); 
            
            // Logo principal (para navbar, header)
            $table->string('logo_url', 500)->nullable();
            
            // Logo con animación (para hero section)
            $table->string('logo_animation_url', 500)->nullable();
             // Logo alternativo (para footer, blanco/oscuro)
            $table->string('logo_alt_url', 500)->nullable();
            // fondo de login
            $table->string('background_login_url', 500)->nullable();
            // Imagen de favicon
            $table->string('favicon_url', 500)->nullable();
            // Imagen de hero background (background principal)
            $table->string('hero_background_url', 500)->nullable();
                // Imagen de hero background (background movil)
            $table->string('background_mobile_url', 500)->nullable();
                // Imagen nosotros ( Nosotros)
            $table->string('nosotros_url', 500)->nullable();
            // Imagen certificación ( Nosotros)
            $table->string('certificacion_url', 500)->nullable();
                // Imagen historia ( Nosotros)
            $table->string('historia_url', 500)->nullable();
            // Imagen de banner para redes sociales
            $table->string('social_banner_url', 500)->nullable();            
            // ─────────────────────────────────────────────────────
            // COLORES HEXADECIMALES - IDENTIDAD CROMÁTICA
            // ─────────────────────────────────────────────────────
            
            // Color primario (rojo expediciones)
            $table->string('color_primario', 7);
            
            // Color secundario (rojo (h1,y títulos secundarios)
            $table->string('color_secundario', 7);
            
            // Color terciario (Verde (botones  degrado y acentos secundarios))
            $table->string('color_terciario', 7);
            
            // Color de acento (negro (botones degrados)
            $table->string('color_acento', 7);
            
            // Color de texto principal
            $table->string('color_texto_primario', 7);
            
            // Color de texto secundario
            $table->string('color_texto_secundario', 7);
            
            // Color de fondo (blanco por defecto)
            $table->string('color_fondo', 7);
            
            // Color de fondo alterno (gris claro)
            $table->string('color_fondo_alterno', 7);
            
           
            // ─────────────────────────────────────────────────────
            // INFORMACIÓN DE LA EMPRESA - TEXTOS HERO
            // ─────────────────────────────────────────────────────
            
            // Nombre de la empresa
            $table->string('nombre_empresa', 255);
            
            // ─────────────────────────────────────────────────────
            // INFORMACIÓN DE CONTACTO
            // ─────────────────────────────────────────────────────
            
            // Email de contacto principal
            $table->string('email_contacto', 255)->nullable();
            
            // Teléfono principal
            $table->string('telefono_principal', 20)->nullable();
            
            // Dirección física
            $table->text('direccion_fisica')->nullable();
 
            // ─────────────────────────────────────────────────────
            // REDES SOCIALES
            // ─────────────────────────────────────────────────────
            
            // Facebook URL
            $table->string('facebook_url', 500)->nullable();
            
            // Instagram URL
            $table->string('instagram_url', 500)->nullable();
            
                // YouTube URL
            $table->string('youtube_url', 500)->nullable();
            
            // WhatsApp número (sin +)
            $table->string('whatsapp_numero', 20)->nullable();
            
            // TikTok URL
            $table->string('tiktok_url', 500)->nullable();
            
            // ─────────────────────────────────────────────────────
            // SEO Y METADATOS
            // ─────────────────────────────────────────────────────
            
            // Meta título para SEO
            $table->string('meta_titulo', 60)->nullable();
            
            // Meta descripción para SEO
            $table->string('meta_descripcion', 160)->nullable();
            
            // Keywords para SEO
            $table->string('meta_keywords', 255)->nullable();
            
            // Open Graph Image (para compartir en redes)
            $table->string('og_image_url', 500)->nullable();
            
            // ─────────────────────────────────────────────────────
            // AUDITORÍA
            // ─────────────────────────────────────────────────────
            
            $table->timestamps();
            $table->softDeletes();
            $table->string('ultima_modificacion_por')->nullable();
            
            
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configuraciones');
    }
};