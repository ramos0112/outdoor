<?php

namespace App\Services;

use App\Models\Ruta;
use Illuminate\Http\Request;

class RutaDataTableService
{
    public function procesar(Request $request)
    {
        $query = Ruta::query();

        // 1. Motor de búsqueda global de DataTables
        if ($request->has('search') && !empty($request->input('search')['value'])) {
            $searchValue = $request->input('search')['value'];

            $query->where(function ($q) use ($searchValue) {
                $q->where('id_ruta', 'LIKE', "%{$searchValue}%")
                  ->orWhere('nombre_ruta', 'LIKE', "%{$searchValue}%")
                  ->orWhere('descripcion_general', 'LIKE', "%{$searchValue}%")
                  ->orWhere('tipo', 'LIKE', "%{$searchValue}%")
                  ->orWhere('precio_regular', 'LIKE', "%{$searchValue}%")
                  ->orWhere('descuento', 'LIKE', "%{$searchValue}%")
                  ->orWhere('precio_actual', 'LIKE', "%{$searchValue}%")
                  ->orWhere('hora_salida', 'LIKE', "%{$searchValue}%")
                  ->orWhere('dificultad', 'LIKE', "%{$searchValue}%")
                  ->orWhere('estado', 'LIKE', "%{$searchValue}%");
            });
        }

        $totalRecords = Ruta::count();
        $filteredRecords = $query->count();

        // 2. Paginación nativa de DataTables
        $start = intval($request->input('start', 0));
        $length = intval($request->input('length', 10));

        $rutas = $query->orderBy('id_ruta', 'desc')
                       ->skip($start)
                       ->take($length)
                       ->get();

        $user = auth()->user();
        $data = [];

        foreach ($rutas as $ruta) {
            $id = $ruta->id_ruta;
            $nombre = htmlspecialchars($ruta->nombre_ruta, ENT_QUOTES, 'UTF-8');
            $descripcion = htmlspecialchars($ruta->descripcion_general, ENT_QUOTES, 'UTF-8');
            $tipo = htmlspecialchars($ruta->tipo, ENT_QUOTES, 'UTF-8');
            $precioRegular = htmlspecialchars($ruta->precio_regular, ENT_QUOTES, 'UTF-8');
            $descuento = htmlspecialchars($ruta->descuento, ENT_QUOTES, 'UTF-8');
            $precioActual = htmlspecialchars($ruta->precio_actual, ENT_QUOTES, 'UTF-8');
            $horaSalida = htmlspecialchars($ruta->hora_salida, ENT_QUOTES, 'UTF-8');
            $dificultad = htmlspecialchars($ruta->dificultad, ENT_QUOTES, 'UTF-8');
            $estado = htmlspecialchars($ruta->estado, ENT_QUOTES, 'UTF-8');

            $botones = '<div class="d-flex justify-content-center" style="gap: 5px;">';

            // Botón Ver (abre el modal único en modo lectura)
            $botones .= '<button type="button" class="btn btn-info btn-sm btn-ver-ruta" data-bs-toggle="modal" data-bs-target="#modalEditarRuta" data-action="show" data-id="' . $id . '" data-nombre_ruta="' . $nombre . '" data-descripcion_general="' . $descripcion . '" data-tipo="' . $tipo . '" data-precio_regular="' . $precioRegular . '" data-descuento="' . $descuento . '" data-precio_actual="' . $precioActual . '" data-hora_salida="' . $horaSalida . '" data-dificultad="' . $dificultad . '" data-estado="' . $estado . '"><i class="bi bi-eye-fill"></i></button>';

            // Botón Editar
            if ($user->can('rutas.editar')) {
                $botones .= '<button type="button" class="btn btn-warning btn-sm btn-editar-ruta" data-bs-toggle="modal" data-bs-target="#modalEditarRuta" data-action="edit" data-id="' . $id . '" data-nombre_ruta="' . $nombre . '" data-descripcion_general="' . $descripcion . '" data-tipo="' . $tipo . '" data-precio_regular="' . $precioRegular . '" data-descuento="' . $descuento . '" data-precio_actual="' . $precioActual . '" data-hora_salida="' . $horaSalida . '" data-dificultad="' . $dificultad . '" data-estado="' . $estado . '"><i class="bi bi-pencil-square"></i></button>';
            }

            // Botón Eliminar
            if ($user->can('rutas.eliminar')) {
                $botones .= '<button type="button" class="btn btn-danger btn-sm btn-eliminar-ruta" data-action="delete" data-id="' . $id . '" data-nombre_ruta="' . $nombre . '"><i class="bi bi-trash"></i></button>';
            }

            $botones .= '</div>';

            $data[] = [
                $ruta->id_ruta,
                $ruta->nombre_ruta,
                $ruta->descripcion_general,
                $ruta->tipo,
                'S/. ' . number_format($ruta->precio_regular, 2),
                'S/. ' . number_format($ruta->descuento, 2),
                'S/. ' . number_format($ruta->precio_actual, 2),
                $ruta->hora_salida,
                $ruta->dificultad,
                $ruta->estado,
                $botones,
            ];
        }

        return [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ];
    }
}
