<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConfiguracionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit()
    {
        $config = Configuracion::obtener();
        // Asegúrate de que la vista coincida con tu estructura de carpetas
        return view('admin.configuracion', compact('config'));
    }

    /**
     * Actualizar configuración
     */
    public function update(Request $request)
    {
        // 1. Validar solo los campos que existen en tu migración
        $validated = $request->validate([
            'nombre_empresa'         => 'required|string|max:255',
            'email_contacto'         => 'nullable|email|max:255',
            'telefono_principal'     => 'nullable|string|max:20',
            'direccion_fisica'       => 'nullable|string|max:255',
            
            // Colores
            'color_primario'         => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'color_secundario'       => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'color_terciario'        => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'color_acento'           => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'color_texto_primario'   => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'color_texto_secundario' => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'color_fondo'            => 'nullable|regex:/^#[0-9A-F]{6}$/i',
            'color_fondo_alterno'    => 'nullable|regex:/^#[0-9A-F]{6}$/i',

            // Redes sociales
            'facebook_url'           => 'nullable|url',
            'instagram_url'          => 'nullable|url',
            'youtube_url'            => 'nullable|url',
            'tiktok_url'             => 'nullable|url',
            'whatsapp_numero'        => 'nullable|string|max:20',

            // SEO
            'meta_titulo'            => 'nullable|string|max:255',
            'meta_descripcion'       => 'nullable|string|max:500',
            'meta_keywords'          => 'nullable|string|max:500',

            // Imágenes
            'logo_url'               => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'logo_animation_url'     => 'nullable|image|max:2048',
            'logo_alt_url'           => 'nullable|image|max:2048',
            'background_login_url'   => 'nullable|image|max:3072',
            'favicon_url'            => 'nullable|image|mimes:png,jpg,ico|max:512',
            'hero_background_url'    => 'nullable|image|max:4096',
            'background_mobile_url'  => 'nullable|image|max:3072',
            'nosotros_url'           => 'nullable|image|max:3072',
            'certificacion_url'      => 'nullable|image|max:3072',
            'historia_url'           => 'nullable|image|max:3072',
            'social_banner_url'      => 'nullable|image|max:3072',
        ]);

        $config = Configuracion::obtener();

        // 2. Procesar imágenes (Solo las que existen en tu base de datos)
        $imagenes = [
            'logo_url'              => ['logo', 400, 200],
            'logo_animation_url'    => ['logo-anim', 400, 200],
            'logo_alt_url'          => ['logo-alt', 400, 200],
            'background_login_url'  => ['bg-login', 1920, 1080],
            'favicon_url'           => ['favicon', 64, 64],
            'hero_background_url'   => ['hero-bg', 1920, 1080],
            'background_mobile_url' => ['bg-mobile', 1080, 1920],
            'nosotros_url'          => ['nosotros', 1200, 800],
            'certificacion_url'     => ['cert', 800, 800],
            'historia_url'          => ['historia', 1200, 800],
            'social_banner_url'     => ['social', 1200, 630],
        ];

        foreach ($imagenes as $campo => [$nombre, $ancho, $alto]) {
            if ($request->hasFile($campo)) {
                // Eliminar anterior
                if ($config->{$campo}) {
                    $pathAnterior = str_replace('/storage/', 'public/', $config->{$campo});
                    Storage::delete($pathAnterior);
                }

                // Optimizar y guardar
                $ruta = $this->optimizarImagen($request->file($campo), $nombre, $ancho, $alto);
                $validated[$campo] = '/storage/' . $ruta;
            }
        }

        // 3. Guardar cambios
        $config->update($validated + [
            'ultima_modificacion_por' => auth()->user()->email,
        ]);

        return redirect()->back()->with('success', '✅ Configuración actualizada y optimizada');
    }

    /**
     * Lógica de Optimización GD (Mantenida del agente)
     */
    private function optimizarImagen($archivo, $nombre, $ancho, $alto)
    {
        // Asegurarse de que exista el directorio en el disco 'public'
        $directorio = 'public/branding';
        if (!Storage::exists($directorio)) {
            Storage::makeDirectory($directorio);
        }

        // Si GD no está disponible o las funciones faltan, no intentar optimizar
        if (!function_exists('imagecreatefromjpeg') || !function_exists('imagewebp')) {
            $nombre_archivo = $nombre . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            return $archivo->storeAs('branding', $nombre_archivo, 'public');
        }

        try {
            $ruta_temporal = $archivo->getRealPath();
            $tipo_imagen = $archivo->getMimeType();

            // Crear recurso según tipo (fallback a storeAs si el tipo no se reconoce)
            switch ($tipo_imagen) {
                case 'image/jpeg': $img_original = imagecreatefromjpeg($ruta_temporal); break;
                case 'image/png':  $img_original = imagecreatefrompng($ruta_temporal); break;
                case 'image/webp': $img_original = imagecreatefromwebp($ruta_temporal); break;
                default:
                    $nombre_archivo = $nombre . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                    return $archivo->storeAs('branding', $nombre_archivo, 'public');
            }

            $ancho_orig = imagesx($img_original);
            $alto_orig = imagesy($img_original);
            $ratio = min($ancho / $ancho_orig, $alto / $alto_orig);
            $n_ancho = (int)($ancho_orig * $ratio);
            $n_alto = (int)($alto_orig * $ratio);

            $img_res = imagecreatetruecolor($n_ancho, $n_alto);

            // Transparencia
            imagealphablending($img_res, false);
            imagesavealpha($img_res, true);

            imagecopyresampled($img_res, $img_original, 0, 0, 0, 0, $n_ancho, $n_alto, $ancho_orig, $alto_orig);

            $nombre_final = $nombre . '_' . time() . '.webp';
            $ruta_final = storage_path('app/public/branding/' . $nombre_final);

            // Guardar como webp (si falla, fallback a storeAs)
            if (!@imagewebp($img_res, $ruta_final, 80)) {
                // Liberar recursos
                imagedestroy($img_original);
                imagedestroy($img_res);
                $nombre_archivo = $nombre . '_' . time() . '.' . $archivo->getClientOriginalExtension();
                return $archivo->storeAs('branding', $nombre_archivo, 'public');
            }

            imagedestroy($img_original);
            imagedestroy($img_res);

            return 'branding/' . $nombre_final;
        } catch (\Throwable $e) {
            // Si ocurre cualquier error en el proceso de optimización, guardar el archivo original
            $nombre_archivo = $nombre . '_' . time() . '.' . $archivo->getClientOriginalExtension();
            return $archivo->storeAs('branding', $nombre_archivo, 'public');
        }
    }
}