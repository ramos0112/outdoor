<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\Ruta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImagenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:imagenes.ver')->only(['index', 'show']);
        $this->middleware('can:imagenes.crear')->only(['create', 'store']);
        $this->middleware('can:imagenes.editar')->only(['edit', 'update']);
        $this->middleware('can:imagenes.eliminar')->only(['destroy']); 
    }
    public function index()
    {
    $imagenes = Imagen::with('ruta')
        ->whereNotNull('url_imagen')
        ->get();

    $rutas = Ruta::all();

    return view('imagen.index', compact('imagenes', 'rutas'));

    }
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'imagen_archivo' => 'required|image|max:2048' // Forzamos archivo para esta prueba
        ]);

        if ($request->hasFile('imagen_archivo')) {
            $file = $request->file('imagen_archivo');
            
            // 1. Preparamos la URL de Cloudinary con tu Cloud Name
            $url = "https://api.cloudinary.com/v1_1/" . env('CLOUDINARY_CLOUD_NAME') . "/image/upload";

            // 2. Enviamos la petición POST
                $timestamp = time();

                $paramsToSign = "timestamp=$timestamp&upload_preset=" 
                . env('CLOUDINARY_UPLOAD_PRESET') 
                . env('CLOUDINARY_API_SECRET');

                $signature = sha1($paramsToSign);

                $response = Http::attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )->post($url, [
                    'api_key'       => env('CLOUDINARY_API_KEY'),
                    'timestamp'     => $timestamp,
                    'signature'     => $signature,
                    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
                ]);

            if ($response->successful()) {
                // 3. Extraemos la URL segura que nos da Cloudinary
                $data = $response->json();
                $url_final = $data['secure_url'];

                // 4. Guardamos en la Base de Datos
                Imagen::create([
                    'id_ruta' => $request->id_ruta,
                    'url_imagen' => $url_final
                ]);

                return redirect()->route('imagen.index')->with('success', 'Imagen subida a la nube con éxito');
            }
        }

        return back()->with('error', 'Error al subir la imagen a Cloudinary');
    }

    public function show($id)
    {
        $imagen = Imagen::findOrFail($id);
        return view('imagen.show', compact('imagen'));
    }

    public function edit($id)
    {
        $imagen = Imagen::findOrFail($id);
        $rutas = Ruta::all();
        return view('imagen.edit', compact('imagen', 'rutas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'imagen_archivo' => 'nullable|image|max:2048',
        ]);

        $imagen = Imagen::findOrFail($id);
        $url_final = $imagen->url_imagen;

        if ($request->hasFile('imagen_archivo')) {
            // A. Si ya existía una imagen en Cloudinary, la borramos antes de subir la nueva
            if (strpos($imagen->url_imagen, 'cloudinary.com') !== false) {
                $this->borrarImagenCloudinary($imagen->url_imagen);
            }

            // B. Subir la nueva imagen (mismo código que usaste en store)
            $file = $request->file('imagen_archivo');
            $url_cloud = "https://api.cloudinary.com/v1_1/" . env('CLOUDINARY_CLOUD_NAME') . "/image/upload";
            
            $timestamp = time();

            $paramsToSign = "timestamp=$timestamp&upload_preset=" 
            . env('CLOUDINARY_UPLOAD_PRESET') 
            . env('CLOUDINARY_API_SECRET');

            $signature = sha1($paramsToSign);

            $response = Http::attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post($url_cloud, [
                'api_key'       => env('CLOUDINARY_API_KEY'),
                'timestamp'     => $timestamp,
                'signature'     => $signature,
                'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),
            ]);  

            if ($response->successful()) {
                $url_final = $response->json()['secure_url'];
            }
        }

        $imagen->update([
            'id_ruta' => $request->id_ruta,
            'url_imagen' => $url_final
        ]);

        return redirect()->route('imagen.index')->with('success', 'Imagen actualizada y anterior eliminada');
    }

    public function destroy($id)
    {
        $imagen = Imagen::findOrFail($id);

        // 1. Borrar de Cloudinary si es una URL de Cloudinary
        if (strpos($imagen->url_imagen, 'cloudinary.com') !== false) {
            $this->borrarImagenCloudinary($imagen->url_imagen);
        }

        // 2. Borrar de la base de datos
        $imagen->delete();

        return redirect()->route('imagen.index')->with('success', 'Imagen eliminada de la base de datos y de la nube');
    }

    private function borrarImagenCloudinary($url_completa)
    {
        // 1. Extraer el Public ID correctamente
        // Buscamos lo que está después de /upload/ y antes de la extensión
        if (!preg_match('/\/upload\/(?:v\d+\/)?(.+)\.[a-z]+$/i', $url_completa, $matches)) {
            return;
        }
        $public_id = $matches[1]; 

        $cloudName = env('CLOUDINARY_CLOUD_NAME');
        $apiKey    = env('CLOUDINARY_API_KEY');
        $apiSecret = env('CLOUDINARY_API_SECRET');
        $timestamp = time();

        // 2. Generar la firma (ORDEN ALFABÉTICO: public_id primero, luego timestamp)
        $paramsString = "public_id=$public_id&timestamp=$timestamp$apiSecret";
        $signature = sha1($paramsString);

        // 3. Petición
        $response = Http::asForm()->post("https://api.cloudinary.com/v1_1/$cloudName/image/destroy", [
            'public_id' => $public_id,
            'api_key'   => $apiKey,
            'timestamp' => $timestamp,
            'signature' => $signature,
        ]);

        // Esto te ayudará a ver en el log si falló y por qué
        if (!$response->successful()) {
            \Log::error("Fallo al borrar en Cloudinary: " . $response->body());
        }
    }
}
