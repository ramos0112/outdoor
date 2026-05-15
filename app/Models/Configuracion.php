<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo Configuracion - White Label
 * * Centraliza todos los elementos dinámicos de branding, colores, logos y configuración
 * de la aplicación en una única tabla.
 */
class Configuracion extends Model
{
    use SoftDeletes;

    protected $table = 'configuraciones';
    protected $primaryKey = 'id_configuracion'; // Ajustado a la migración estándar de Laravel
    public $timestamps = true;

    /**
     * Atributos que pueden ser asignados masivamente
     */
    protected $fillable = [
        // Logos e Imágenes (Sincronizado con tu última migración)
        'logo_url',
        'logo_animation_url',
        'logo_alt_url',
        'background_login_url',
        'favicon_url',
        'hero_background_url',
        'background_mobile_url',
        'nosotros_url',
        'certificacion_url',
        'historia_url',
        'social_banner_url',

        // Colores Hexadecimales
        'color_primario',
        'color_secundario',
        'color_terciario',
        'color_acento',
        'color_texto_primario',
        'color_texto_secundario',
        'color_fondo',
        'color_fondo_alterno',

        // Información Empresa
        'nombre_empresa',

        // Contacto
        'email_contacto',
        'telefono_principal',
        'direccion_fisica',

        // Redes Sociales
        'facebook_url',
        'instagram_url',
        'youtube_url',
        'whatsapp_numero',
        'tiktok_url',

        // SEO y Metadatos
        'meta_titulo',
        'meta_descripcion',
        'meta_keywords',
        'og_image_url',

        // Auditoría
        'ultima_modificacion_por',
    ];

    /**
     * Obtiene la configuración actual (Singleton)
     */
    public static function obtener()
    {
        return self::first() ?: new self();
    }

    /**
     * Genera variables CSS para inyectar en el <head>
     */
    public function generarVariablesCSS(): string
    {
        return "
        :root {
            --color-primario: {$this->color_primario};
            --color-secundario: {$this->color_secundario};
            --color-terciario: {$this->color_terciario};
            --color-acento: {$this->color_acento};
            --color-texto-primario: {$this->color_texto_primario};
            --color-texto-secundario: {$this->color_texto_secundario};
            --color-fondo: {$this->color_fondo};
            --color-fondo-alterno: {$this->color_fondo_alterno};
        }";
    }

    /**
     * Formatea los datos de contacto para las vistas
     */
    public function obtenerContacto(): array
    {
        return [
            'email'     => $this->email_contacto,
            'telefono'  => $this->telefono_principal,
            'direccion' => $this->direccion_fisica,
        ];
    }

    /**
     * Formatea redes sociales filtrando las que estén vacías
     */
    public function obtenerRedesSociales(): array
    {
        return array_filter([
            'facebook'  => $this->facebook_url,
            'instagram' => $this->instagram_url,
            'youtube'   => $this->youtube_url,
            'tiktok'    => $this->tiktok_url,
            'whatsapp'  => $this->whatsapp_numero ? "https://wa.me/" . preg_replace('/[^0-9]/', '', $this->whatsapp_numero) : null,
        ]);
    }
}