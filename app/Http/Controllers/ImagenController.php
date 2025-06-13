<?php

namespace App\Http\Controllers;

use App\Models\Imagen;
use App\Models\Ruta;
use Illuminate\Http\Request;

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
        $imagenes = Imagen::with('ruta')->get();
        $rutas = Ruta::all();
        return view('imagenes.index', compact('imagenes', 'rutas')); // 👈 Esto es solo para testear
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'url_imagen' => 'nullable|url',
            'imagen_archivo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048'
        ]);

        $url_imagen = null;

        // Si sube un archivo, se usa ese
        if ($request->hasFile('imagen_archivo')) {
            $path = $request->file('imagen_archivo')->store('imagenes', 'public');
            $url_imagen = 'storage/' . $path; // ejemplo: storage/imagenes/foto.jpg
        } elseif ($request->filled('url_imagen')) {
            // Si pegó una URL, usamos esa
            $url_imagen = $request->url_imagen;
        }

        Imagen::create([
            'id_ruta' => $request->id_ruta,
            'url_imagen' => $url_imagen
        ]);

        return redirect()->route('imageness.index')->with('success', 'Imagen añadida exitosamente');
    }

    public function show($id)
    {
        $imagen = Imagen::findOrFail($id);
        return view('imagenes.show', compact('imagen'));
    }

    public function edit($id)
    {
        $imagen = Imagen::findOrFail($id);
        $rutas = Ruta::all();
        return view('imageness.edit', compact('imagen', 'rutas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'url_imagen' => 'nullable|url',
            'imagen_archivo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $imagen = Imagen::findOrFail($id);

        // Si sube una nueva imagen, se reemplaza
        if ($request->hasFile('imagen_archivo')) {
            $path = $request->file('imagen_archivo')->store('imagenes', 'public');
            $imagen->url_imagen = 'storage/' . $path;
        } elseif ($request->filled('url_imagen')) {
            // Si se pegó una nueva URL externa
            $imagen->url_imagen = $request->url_imagen;
        }
        // Si no subió ni pegó nada, se conserva la imagen actual

        $imagen->id_ruta = $request->id_ruta;
        $imagen->save();

        return redirect()->route('imageness.index')->with('success', 'Imagen actualizada exitosamente');
    }

    public function destroy($id)
    {
        $imagen = Imagen::findOrFail($id);
        $imagen->delete();

        return redirect()->route('imageness.index')->with('success', 'Imagen eliminada exitosamente');
    }
}
