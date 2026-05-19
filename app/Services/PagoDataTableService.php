<?php

namespace App\Services;

use App\Models\Pago;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PagoDataTableService
{
    public function procesar(Request $request)
    {
        $query = Pago::query();

        // 1. Motor de búsqueda global
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            
            $query->where(function($q) use ($searchValue) {
                $q->where('id_pago', 'LIKE', "%{$searchValue}%")
                  ->orWhere('id_reserva', 'LIKE', "%{$searchValue}%")
                  ->orWhere('metodo_pago', 'LIKE', "%{$searchValue}%")
                  ->orWhere('monto_pagado', 'LIKE', "%{$searchValue}%");
            });
        }

        $totalRecords = Pago::count();
        $filteredRecords = $query->count();

        // 2. Paginación controlada por DataTables
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        
        $pagos = $query->orderBy('id_pago', 'desc')
                       ->skip($start)
                       ->take($length)
                       ->get();

        // 3. Formatear la estructura JSON de respuesta
        $data = [];
        foreach ($pagos as $pago) {
            
            // Botón Ver único y dinámico pasando la data como JSON string
            $botones = '<div class="d-flex justify-content-center">';
            $botones .= '<button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalShowPago" data-pago=\'' . json_encode($pago) . '\' title="Ver"><i class="bi bi-eye-fill"></i></button>';
            $botones .= '</div>';

            $data[] = [
                $pago->id_pago,
                $pago->id_reserva,
                $pago->metodo_pago,
                'S/. ' . number_format($pago->monto_pagado, 2),
                $pago->fecha_pago ? Carbon::parse($pago->fecha_pago)->format('d/m/Y H:i') : 'Sin fecha',
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