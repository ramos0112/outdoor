<?php

namespace App\Services;

use App\Models\Imagen;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ImagenDataTableService
{
    public function procesar(Request $request)
    {
        $query = Imagen::with('ruta')->whereNotNull('url_imagen');

        // Búsqueda
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('id_imagen', 'LIKE', "%{$searchValue}%")
                  ->orWhereHas('ruta', function($row) use ($searchValue) {
                      $row->where('nombre_ruta', 'LIKE', "%{$searchValue}%");
                  });
            });
        }

        $totalRecords = Imagen::count();
        $filteredRecords = $query->count();

        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $imagenes = $query->orderBy('id_imagen', 'desc')->skip($start)->take($length)->get();

        $data = [];
        foreach ($imagenes as $img) {
            $user = auth()->user();
            // Determinar la URL de la imagen
            $url = Str::startsWith($img->url_imagen, 'http') ? $img->url_imagen : asset($img->url_imagen);
            
            $htmlImagen = '<img src="'.$url.'" alt="Ruta" width="70" class="img-thumbnail">';
            
            $botones = '<div class="d-flex justify-content-center" style="gap: 5px;">';
            $botones .= '<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShow" data-objeto=\'' . json_encode($img) . '\'><i class="fas fa-eye"></i></button>';
            
            if ($user->can('imagenes.editar')) {
                $botones .= '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit" data-objeto=\'' . json_encode($img) . '\'><i class="fas fa-pencil-alt"></i></button>';
            }
            
            if ($user->can('imagenes.eliminar')) {
                $botones .= '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete" data-objeto=\'' . json_encode($img) . '\'><i class="fas fa-trash-alt"></i></button>';
            }
            $botones .= '</div>';

            $data[] = [
                $img->id_imagen,
                $img->ruta->nombre_ruta ?? 'N/A',
                $htmlImagen,
                $botones
            ];
        }

        return [
            "draw" => intval($request->input('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $filteredRecords,
            "data" => $data
        ];
    }
}