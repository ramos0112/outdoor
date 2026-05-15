<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Configuracion;
use Illuminate\Support\Facades\DB;

class ConfiguracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('configuraciones')->updateOrInsert(
    [
            // ===================== LOGOS E IMÁGENES =====================
            'logo_url'              => '/imagenes/logo.png',
            'logo_animation_url'    => '/imagenes/logo_animation.png',
            'logo_alt_url'          => '/imagenes/logo_alt.png',
            'background_login_url'  => '/imagenes/background_login.png',
            'favicon_url'           => '/favicon.ico',
            'hero_background_url'   => '/imagenes/hero_background.png',
            'background_mobile_url' => '/imagenes/background_mobile.png',
            'nosotros_url'          => '/imagenes/nosotros.png',
            'certificacion_url'     => '/imagenes/certificacion.png',
            'historia_url'          => '/imagenes/historia.png',
            'social_banner_url'     => '/imagenes/social_banner.png',
            // ===================== COLORES HEXADECIMALES =====================
            'color_primario'         => '#dc030c', // Rojo Outdoor
            'color_secundario'       => '#b42a2f', // Rojo títulos
            'color_terciario'        => '#2D5016', // Verde acentos
            'color_acento'           => '#302b29', // Negro acento
            'color_texto_primario'   => '#333333', // Texto principal
            'color_texto_secundario' => '#666666', // Texto secundario
            'color_fondo'            => '#FFFFFF', // Fondo
            'color_fondo_alterno'    => '#F5F5F5', // Fondo alterno
            // ===================== INFORMACIÓN EMPRESA =====================
            'nombre_empresa'      => 'Outdoor Expeditions',
            // ===================== CONTACTO =====================
            'email_contacto'      => 'contacto@outdoorexpeditions.com',
            'telefono_principal'  => '+51 961358621',
            'direccion_fisica'    => 'Calle Principal 123, Lima, Perú',
            // ===================== REDES SOCIALES =====================
            'facebook_url'    => 'https://facebook.com/OutdoorExpeditions',
            'instagram_url'   => 'https://instagram.com/OutdoorExpeditions',
            'youtube_url'     => 'https://youtube.com/@OutdoorExpeditions',
            'whatsapp_numero' => '51 961358621',
            'tiktok_url'      => 'https://tiktok.com/@outdoorexp',
            // ===================== SEO Y METADATOS =====================
            'meta_titulo'      => 'Outdoor Expeditions | Aventuras Extremas',
            'meta_descripcion' => 'Descubre tours de aventura únicos con guías certificados.',
            'meta_keywords'    => 'aventura, trekking, naturaleza, outdoor',
            'og_image_url'     => '/imagenes/social_banner.png',
            // ===================== AUDITORÍA =====================
            'ultima_modificacion_por' => 'admin@outdoorexpeditions.com',
        ]);

        $this->command->info('✅ Seeder actualizado con éxito.');
    }
}