<?php

namespace App\Services;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteDataTableService
{
    public function procesar(Request $request)
    {
        $query = Cliente::query();

        // 1. Motor de búsqueda global (Filtra por campos principales)
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            
            $query->where(function($q) use ($searchValue) {
                $q->where('id_cliente', 'LIKE', "%{$searchValue}%")
                  ->orWhere('nombre', 'LIKE', "%{$searchValue}%")
                  ->orWhere('apellido', 'LIKE', "%{$searchValue}%")
                  ->orWhere('numero_documento', 'LIKE', "%{$searchValue}%")
                  ->orWhere('email', 'LIKE', "%{$searchValue}%")
                  ->orWhere('telefono', 'LIKE', "%{$searchValue}%")
                  ->orWhere('ciudad', 'LIKE', "%{$searchValue}%");
            });
        }

        $totalRecords = Cliente::count();
        $filteredRecords = $query->count();

        // 2. Paginación de DataTables
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        
        $clientes = $query->orderBy('id_cliente', 'desc')
                          ->skip($start)
                          ->take($length)
                          ->get();

        // 3. Formatear la respuesta
        $data = [];
        foreach ($clientes as $cliente) {
            
            // Evaluamos permisos en backend para armar los botones dinámicos
            $user = auth()->user();
            $botones = '<div class="d-flex justify-content-center" style="gap: 5px;">';
            
            // Botón Ver (Siempre visible)
            $botones .= '<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShow" data-cliente=\'' . json_encode($cliente) . '\'><i class="fas fa-eye"></i></button>';
            
            // Botón Editar
            if ($user->can('clientes.editar')) {
                $botones .= '<button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit" data-cliente=\'' . json_encode($cliente) . '\'><i class="fas fa-pencil-alt"></i></button>';
            }
            
            // Botón Eliminar
            if ($user->can('clientes.eliminar')) {
                $botones .= '<button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalDelete" data-cliente=\'' . json_encode($cliente) . '\'><i class="fas fa-trash-alt"></i></button>';
            }
            
            $botones .= '</div>';

            $data[] = [
                $cliente->id_cliente,
                $cliente->nombre,
                $cliente->apellido,
                $cliente->tipo_documento,
                $cliente->numero_documento,
                $cliente->fecha_nacimiento,
                $cliente->email ?? 'Sin email',
                $cliente->telefono ?? 'NULL',
                $cliente->pais ?? 'NULL',
                $cliente->region ?? 'NULL',
                $cliente->ciudad ?? 'NULL',
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