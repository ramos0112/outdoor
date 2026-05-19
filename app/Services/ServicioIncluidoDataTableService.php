<?php

namespace App\Services;

use App\Models\ServicioIncluido;
use Illuminate\Http\Request;

class ServicioIncluidoDataTableService
{
    public function procesar(Request $request)
    {
        $query = ServicioIncluido::with('ruta');

        // 1. Búsqueda global
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            $query->where(function($q) use ($searchValue) {
                $q->where('id_servicio', 'LIKE', "%{$searchValue}%")
                  ->orWhere('servicio', 'LIKE', "%{$searchValue}%")
                  ->orWhereHas('ruta', function($row) use ($searchValue) {
                      $row->where('nombre_ruta', 'LIKE', "%{$searchValue}%");
                  });
            });
        }

        $totalRecords = ServicioIncluido::count();
        $filteredRecords = $query->count();

        // 2. Paginación
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $servicios = $query->orderBy('id_servicio', 'desc')
                           ->skip($start)
                           ->take($length)
                           ->get();

        // 3. Formatear datos para DataTables
        $data = [];
        foreach ($servicios as $servicio) {
            $user = auth()->user();
            $botones = '<div class="d-flex justify-content-center" style="gap: 5px;">';
            
            // Botón Ver
            $botones .= '<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShow" data-objeto=\'' . json_encode($servicio) . '\'><i class="fas fa-eye"></i></button>';
            
            // Botón Editar
            if ($user->can('servicios.editar')) {
                $botones .= '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit" data-objeto=\'' . json_encode($servicio) . '\'><i class="fas fa-pencil-alt"></i></button>';
            }
            
            // Botón Eliminar
            if ($user->can('servicios.eliminar')) {
                $botones .= '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete" data-objeto=\'' . json_encode($servicio) . '\'><i class="fas fa-trash-alt"></i></button>';
            }
            $botones .= '</div>';

            $data[] = [
                $servicio->id_servicio,
                $servicio->ruta->nombre_ruta ?? 'N/A',
                $servicio->servicio,
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