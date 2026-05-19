<?php

namespace App\Services;

use App\Models\Reserva;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservaDataTableService
{
    public function procesar(Request $request)
    {
        $query = Reserva::with(['fechaDisponible.ruta', 'clientes', 'movilidads.guias']);

        // 1. Motor de filtrado y búsqueda global
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            
            $query->where(function($q) use ($searchValue) {
                $q->where('id_reserva', 'LIKE', "%{$searchValue}%")
                  ->orWhere('estado', 'LIKE', "%{$searchValue}%")
                  ->orWhereHas('clientes', function($subQ) use ($searchValue) {
                      $subQ->where('nombre', 'LIKE', "%{$searchValue}%")
                           ->orWhere('apellido', 'LIKE', "%{$searchValue}%")
                           ->orWhere('numero_documento', 'LIKE', "%{$searchValue}%");
                  })
                  ->orWhereHas('fechaDisponible.ruta', function($subQ) use ($searchValue) {
                      $subQ->where('nombre_ruta', 'LIKE', "%{$searchValue}%");
                  })
                  ->orWhereHas('movilidads', function($subQ) use ($searchValue) {
                      $subQ->where('conductor', 'LIKE', "%{$searchValue}%");
                  });
            });
        }

        $totalRecords = Reserva::count();
        $filteredRecords = $query->count();

        // 2. Paginación indexada por DataTables
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        
        $reservas = $query->orderBy('id_reserva', 'desc')
                          ->skip($start)
                          ->take($length)
                          ->get();

        // 3. Estructuración y formateo del JSON
        $data = [];
        foreach ($reservas as $reserva) {
            $clientesHtml = '';
            $dnisHtml = '';
            foreach ($reserva->clientes as $cliente) {
                $clientesHtml .= "• {$cliente->nombre} {$cliente->apellido}<br>";
                $dnisHtml .= "{$cliente->numero_documento}<br>";
            }

            $conductoresHtml = '';
            $guiasHtml = '';
            foreach ($reserva->movilidads as $movilidad) {
                $conductoresHtml .= ($movilidad->conductor ?? 'Sin placa') . '<br>';
                foreach ($movilidad->guias as $guia) {
                    $guiasHtml .= "{$guia->nombre} {$guia->apellido}<br>";
                }
            }

            $badgeClass = $reserva->estado == 'Pagado' ? 'badge-success' : 'badge-warning';
            $estadoHtml = '<span class="badge ' . $badgeClass . '">' . strtoupper($reserva->estado) . '</span>';

            $data[] = [
                $reserva->id_reserva,
                $reserva->fechaDisponible->ruta->nombre_ruta ?? 'Sin ruta',
                $conductoresHtml,
                $guiasHtml,
                $clientesHtml,
                $dnisHtml,
                $reserva->fecha_reserva ? Carbon::parse($reserva->fecha_reserva)->format('d/m/Y') : '',
                $reserva->fechaDisponible->fecha ? Carbon::parse($reserva->fechaDisponible->fecha)->format('d/m/Y') : '',
                $reserva->cantidad_personas,
                'S/. ' . number_format($reserva->precio_total, 2),
                '<span class="text-danger">S/. ' . number_format($reserva->saldo, 2) . '</span>',
                $estadoHtml
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