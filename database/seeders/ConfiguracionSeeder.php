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
            'logo_url'              => '/imagenes/logo.webp',
            'logo_animation_url'    => '/imagenes/logo-anim.webp',
            'logo_alt_url'          => '/imagenes/logo_alt.webp',
            'background_login_url'  => '/imagenes/background_login.png',
            'favicon_url'           => '/favicon.ico',
            'hero_background_url'   => '/imagenes/hero_background.png',
            'background_mobile_url' => '/imagenes/background_mobile.png',
            'nosotros_url'          => '/imagenes/nosotros.png',
            'certificacion_url'     => '/imagenes/certificado.webp',
            'historia_url'          => '/imagenes/historia.webp',
            'social_banner_url'     => '/imagenes/social_banner.png',
            // ===================== COLORES HEXADECIMALES =====================
            'color_primario'         => '#F7F3EB', 
            'color_secundario'       => '#ee6c11', 
            'color_terciario'        => '#A3B18A', 
            'color_acento'           => '#302b29', 
            'color_texto_primario'   => '#fc6b14', 
            'color_texto_secundario' => '#000000',
            'color_fondo'            => '#FFFFFF', 
            'color_fondo_alterno'    => '#e09b06', 
            // ===================== INFORMACIÓN EMPRESA =====================
            'nombre_empresa'      => 'Ayni Forest',
            // ===================== CONTACTO =====================
            'email_contacto'      => 'ayniforestperu@gmail.com',
            'telefono_principal'  => '+51 933 329 650',
            'direccion_fisica'    => 'Av. Geronimo de 253, Trujillo 13007, Perú',
            // ===================== REDES SOCIALES =====================
            'facebook_url'    => 'https://facebook.com/OutdoorExpeditions',
            'instagram_url'   => 'https://instagram.com/OutdoorExpeditions',
            'youtube_url'     => 'https://youtube.com/@OutdoorExpeditions',
            'whatsapp_numero' => '51 961358621',
            'tiktok_url'      => 'https://tiktok.com/@outdoorexp',
            // ===================== SEO Y METADATOS =====================
            'meta_titulo'      => 'Ayni Forest | Aventuras Extremas',
            'meta_descripcion' => 'Descubre tours de aventura únicos con guías certificados.',
            'meta_keywords'    => 'aventura, trekking, naturaleza, outdoor',
            'og_image_url'     => '/imagenes/social_banner.png',
            // ===================== AUDITORÍA =====================
            'ultima_modificacion_por' => 'ayniforestperu@gmail.com',
        ]);

        $this->command->info('✅ Seeder actualizado con éxito.');
    }
}