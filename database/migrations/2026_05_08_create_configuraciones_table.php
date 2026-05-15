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
            $table->string('logo_url', 500)->nullable()
                ->comment('Logo principal - ubicación: /public/imagenes/logo.png');
            
            // Logo con animación (para hero section)
            $table->string('logo_animation_url', 500)->nullable()
                ->comment('Logo con animación - ubicación: /public/imagenes/logo_animation.png');
            
            // Logo alternativo (para footer, blanco/oscuro)
            $table->string('logo_alt_url', 500)->nullable()
                ->comment('Logo alternativo para fondo oscuro - ubicación: /public/imagenes/logo_alt.png');

                // fondo de login
            $table->string('background_login_url', 500)->nullable()
                ->comment('Fondo para página de login -ubicación: /public/imagenes/background_login.png');
            
            // Imagen de favicon
            $table->string('favicon_url', 500)->nullable()
                ->comment('Favicon del sitio - ubicación: /public/favicon.ico');
            
            // Imagen de hero background (background principal)
            $table->string('hero_background_url', 500)->nullable()
                ->comment('Background para hero section- ubicación: /public/imagenes/hero_background.png');

                // Imagen de hero background (background movil)
            $table->string('background_mobile_url', 500)->nullable()
                ->comment('Background para hero section -ubicación: /public/imagenes/background_mobile.png');

                // Imagen nosotros ( Nosotros)
            $table->string('nosotros_url', 500)->nullable()
                ->comment('nosotros -ubicación: /public/imagenes/nosotros.png');

            // Imagen certificación ( Nosotros)
            $table->string('certificacion_url', 500)->nullable()
                ->comment('certificación -ubicación: /public/imagenes/certificacion.png');
            
                // Imagen historia ( Nosotros)
            $table->string('historia_url', 500)->nullable()
                ->comment('historia -ubicación: /public/imagenes/historia.png');

            // Imagen de banner para redes sociales
            $table->string('social_banner_url', 500)->nullable()
                ->comment('Banner para redes sociales - ubicación: /public/imagenes/social_banner.png (1200x630)');
            
            // ─────────────────────────────────────────────────────
            // COLORES HEXADECIMALES - IDENTIDAD CROMÁTICA
            // ─────────────────────────────────────────────────────
            
            // Color primario (rojo expediciones)
            $table->string('color_primario', 7)->default('#dc030c')
                ->comment('Color primario hex - Ej: #dc030c (rojo Outdoor)');
            
            // Color secundario (rojo (h1,y títulos secundarios)
            $table->string('color_secundario', 7)->default('#b42a2f')
                ->comment('Color secundario hex - Ej: #b42a2f (rojo)');
            
            // Color terciario (Verde (botones  degrado y acentos secundarios))
            $table->string('color_terciario', 7)->default('#2D5016')
                ->comment('Color terciario hex - Ej: #2D5016 ');
            
            // Color de acento (negro (botones degrados)
            $table->string('color_acento', 7)->default('#302b29')
                ->comment('Color de acento hex - Ej: #302b29 ');
            
            // Color de texto principal
            $table->string('color_texto_primario', 7)->default('#333333')
                ->comment('Color texto principal');
            
            // Color de texto secundario
            $table->string('color_texto_secundario', 7)->default('#666666')
                ->comment('Color texto secundario');
            
            // Color de fondo (blanco por defecto)
            $table->string('color_fondo', 7)->default('#FFFFFF')
                ->comment('Color de fondo');
            
            // Color de fondo alterno (gris claro)
            $table->string('color_fondo_alterno', 7)->default('#F5F5F5')
                ->comment('Color de fondo alterno');
            
           
            // ─────────────────────────────────────────────────────
            // INFORMACIÓN DE LA EMPRESA - TEXTOS HERO
            // ─────────────────────────────────────────────────────
            
            // Nombre de la empresa
            $table->string('nombre_empresa', 255)->default('Outdoor Expeditions')
                ->comment('Ej: "Outdoor Expeditions"');
            
            // ─────────────────────────────────────────────────────
            // INFORMACIÓN DE CONTACTO
            // ─────────────────────────────────────────────────────
            
            // Email de contacto principal
            $table->string('email_contacto', 255)->nullable()
                ->comment('Ej: contacto@outdoorexpeditions.com');
            
            // Teléfono principal
            $table->string('telefono_principal', 20)->nullable()
                ->comment('Ej: +51987654321');
            
            // Dirección física
            $table->text('direccion_fisica')->nullable()
                ->comment('Ej: Calle Principal 123, Lima, Perú');
 
            // ─────────────────────────────────────────────────────
            // REDES SOCIALES
            // ─────────────────────────────────────────────────────
            
            // Facebook URL
            $table->string('facebook_url', 500)->nullable()
                ->comment('Ej: https://facebook.com/OutdoorExpeditions');
            
            // Instagram URL
            $table->string('instagram_url', 500)->nullable()
                ->comment('Ej: https://instagram.com/OutdoorExpeditions');
            
                // YouTube URL
            $table->string('youtube_url', 500)->nullable()
                ->comment('Ej: https://youtube.com/@OutdoorExpeditions');
            
            // WhatsApp número (sin +)
            $table->string('whatsapp_numero', 20)->nullable()
                ->comment('Ej: 51987654321 (sin + ni espacios)');
            
            // TikTok URL
            $table->string('tiktok_url', 500)->nullable()
                ->comment('Ej: https://tiktok.com/@outdoorexp');
            
            // ─────────────────────────────────────────────────────
            // SEO Y METADATOS
            // ─────────────────────────────────────────────────────
            
            // Meta título para SEO
            $table->string('meta_titulo', 60)->nullable()
                ->comment('Meta title: máx 60 caracteres');
            
            // Meta descripción para SEO
            $table->string('meta_descripcion', 160)->nullable()
                ->comment('Meta description: 150-160 caracteres');
            
            // Keywords para SEO
            $table->string('meta_keywords', 255)->nullable()
                ->comment('Keywords: "aventura, trekking, naturaleza"');
            
            // Open Graph Image (para compartir en redes)
            $table->string('og_image_url', 500)->nullable()
                ->comment('Imagen para redes sociales: 1200x630px');
            
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