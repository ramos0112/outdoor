<?php

namespace App\Services;

use App\Models\Reserva;
use Illuminate\Http\Request;

class ReservaMovilidadDataTableService
{
    public function procesar(Request $request)
    {
        // Traer solo las reservas que NO tienen movilidad asignada
        $query = Reserva::whereDoesntHave('movilidads')->with('fechaDisponible.ruta');

        // Búsqueda global por ID, Ruta o Estado
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];
            
            $query->where(function($q) use ($searchValue) {
                $q->where('id_reserva', 'LIKE', "%{$searchValue}%")
                  ->orWhere('estado', 'LIKE', "%{$searchValue}%")
                  ->orWhereHas('fechaDisponible.ruta', function($subQ) use ($searchValue) {
                      $subQ->where('nombre_ruta', 'LIKE', "%{$searchValue}%");
                  });
            });
        }

        $totalRecords = Reserva::whereDoesntHave('movilidads')->count();
        $filteredRecords = $query->count();

        // Paginación
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        
        $reservas = $query->skip($start)->take($length)->get();

        $data = [];
        foreach ($reservas as $reserva) {
            // Renderizamos los estados con sus respectivos estilos e iconos
            $estadoHtml = '';
            if ($reserva->estado === 'pendiente') {
                $estadoHtml = '<span class="text-warning"><i class="bi bi-clock-fill"></i> Pendiente</span>';
            } elseif ($reserva->estado === 'confirmado') {
                $estadoHtml = '<span class="text-success"><i class="bi bi-check-circle-fill"></i> Confirmado</span>';
            } else {
                $estadoHtml = '<span class="text-secondary"><i class="bi bi-question-circle-fill"></i> ' . e($reserva->estado) . '</span>';
            }

            // El Checkbox ahora incluye un ID dinámico en el HTML
            $checkboxHtml = '<input type="checkbox" class="reserva-check" value="' . $reserva->id_reserva . '" data-personas="' . $reserva->cantidad_personas . '">';
            
            $data[] = [
                $checkboxHtml,
                $reserva->id_reserva,
                $reserva->fechaDisponible->ruta->nombre_ruta ?? 'Sin ruta',
                $reserva->cantidad_personas,
                $reserva->fechaDisponible->fecha ?? 'Sin fecha',
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