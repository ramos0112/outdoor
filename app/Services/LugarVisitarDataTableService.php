<?php

namespace App\Services;

use App\Models\LugarVisitar;
use Illuminate\Http\Request;

class LugarVisitarDataTableService
{
    public function procesar(Request $request)
    {
        // Cargamos la relación 'ruta' para evitar el problema de consultas N+1
        $query = LugarVisitar::with('ruta');

        // 1. Motor de búsqueda global
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            
            $query->where(function($q) use ($searchValue) {
                $q->where('id_lugar', 'LIKE', "%{$searchValue}%")
                  ->orWhere('nombre_lugar', 'LIKE', "%{$searchValue}%")
                  ->orWhereHas('ruta', function($row) use ($searchValue) {
                      $row->where('nombre_ruta', 'LIKE', "%{$searchValue}%");
                  });
            });
        }

        $totalRecords = LugarVisitar::count();
        $filteredRecords = $query->count();

        // 2. Paginación
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        
        $lugares = $query->orderBy('id_lugar', 'desc')
                         ->skip($start)
                         ->take($length)
                         ->get();

        // 3. Formatear la respuesta
        $data = [];
        foreach ($lugares as $lugar) {
            $user = auth()->user();
            $botones = '<div class="d-flex justify-content-center" style="gap: 5px;">';
            
            // Botón Ver
            // Ejemplo dentro de tu Service:
                $botones .= '<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShow" data-cliente=\'' . json_encode($lugar) . '\'><i class="fas fa-eye"></i></button>';
            
            // Botón Editar
            if ($user->can('lugares.editar')) {
                $botones .= '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit" data-cliente=\'' . json_encode($lugar) . '\'><i class="fas fa-pencil-alt"></i></button>';
            }
            
            // Botón Eliminar (Agregado para seguir la estructura)
            // Dentro del bucle del Service...
            if ($user->can('lugares.eliminar')) {
                $botones .= '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete" data-cliente=\'' . json_encode($lugar) . '\'><i class="fas fa-trash-alt"></i></button>';
            }
            
            $botones .= '</div>';

            $data[] = [
                $lugar->id_lugar,
                $lugar->ruta->nombre_ruta ?? 'N/A',
                $lugar->nombre_lugar,
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