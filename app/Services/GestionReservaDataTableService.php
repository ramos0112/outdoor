<?php

namespace App\Services;

use App\Models\Reserva;
use Illuminate\Http\Request;

class GestionReservaDataTableService
{
    public function procesar(Request $request)
    {
        $query = Reserva::with(['fechaDisponible.ruta', 'clientes', 'movilidads']);

        // 1. Motor de búsqueda global
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            
            $query->where(function($q) use ($searchValue) {
                $q->where('id_reserva', 'LIKE', "%{$searchValue}%")
                  ->orWhere('estado', 'LIKE', "%{$searchValue}%")
                  ->orWhereHas('clientes', function($subQ) use ($searchValue) {
                      $subQ->where('nombre', 'LIKE', "%{$searchValue}%")
                           ->orWhere('numero_documento', 'LIKE', "%{$searchValue}%");
                  })
                  ->orWhereHas('fechaDisponible.ruta', function($subQ) use ($searchValue) {
                      $subQ->where('nombre_ruta', 'LIKE', "%{$searchValue}%");
                  });
            });
        }

        $totalRecords = Reserva::count();
        $filteredRecords = $query->count();

        // 2. Paginación y Orden por última actualización (así se ven arriba los cambios recientes)
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        
        $reservas = $query->orderBy('updated_at', 'desc')
                          ->skip($start)
                          ->take($length)
                          ->get();

        // 3. Estructuración del JSON
        $data = [];
        foreach ($reservas as $reserva) {
            
            $movilidadesHtml = '';
            foreach ($reserva->movilidads as $movilidad) {
                $movilidadesHtml .= "{$movilidad->id_movilidad} -- {$movilidad->conductor}<br>";
            }

            $dnisHtml = '';
            foreach ($reserva->clientes as $cliente) {
                $dnisHtml .= "{$cliente->numero_documento}<br>";
            }

            $clientesHtml = $reserva->clientes->pluck('nombre')->join(', ');

            $data[] = [
                $reserva->id_reserva,
                $reserva->fechaDisponible->ruta->nombre_ruta ?? 'Sin ruta',
                $movilidadesHtml,
                $dnisHtml,
                $clientesHtml,
                $reserva->fechaDisponible->fecha ?? 'Sin fecha',
                'S/. ' . number_format($reserva->precio_total, 2),
                'S/. ' . number_format($reserva->saldo, 2),
                $reserva->estado
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