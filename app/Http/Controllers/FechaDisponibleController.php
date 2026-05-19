<?php

namespace App\Http\Controllers;

use App\Models\FechaDisponible;
use App\Models\Ruta;
use Illuminate\Http\Request;

class FechaDisponibleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:fechas.ver')->only(['index', 'show']);
        $this->middleware('can:fechas.crear')->only(['create', 'store']);
        $this->middleware('can:fechas.editar')->only(['edit', 'update']);
        $this->middleware('can:fechas.eliminar')->only(['destroy']);
    }

public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $fechasQuery = FechaDisponible::with('ruta');

            // Lógica de búsqueda Server-Side de DataTables
            if (!empty($request->input('search.value'))) {
                $searchValue = $request->input('search.value');
                $fechasQuery->where(function($query) use ($searchValue) {
                    $query->where('id_fecha', 'like', "%{$searchValue}%")
                          ->orWhere('fecha', 'like', "%{$searchValue}%")
                          ->orWhereHas('ruta', function($q) use ($searchValue) {
                              $q->where('nombre_ruta', 'like', "%{$searchValue}%");
                          });
                });
            }

            $totalRecords = FechaDisponible::count();
            $filteredRecords = $fechasQuery->count();

            // Paginación y Orden
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            
            $fechas = $fechasQuery->skip($start)->take($length)->orderBy('id_fecha', 'desc')->get();

            $data = [];
            foreach ($fechas as $fecha) {
                $botones = '<div class="d-flex justify-content-center gap-1">';
                
                // Botón Ver (Siempre visible si tiene permiso index/show)
                $botones .= '<button type="button" class="btn btn-info btn-sm btn-accion-fecha" 
                                data-bs-toggle="modal" data-bs-target="#modalAccionFecha" data-action="show"
                                data-id="'.$fecha->id_fecha.'" 
                                data-id_ruta="'.$fecha->id_ruta.'" 
                                data-nombre_ruta="'.($fecha->ruta->nombre_ruta ?? '').'" 
                                data-fecha="'.$fecha->fecha.'" title="Ver">
                                <i class="bi bi-eye-fill"></i>
                             </button>';

                if (auth()->user()->can('fechas.editar')) {
                    $botones .= '<button type="button" class="btn btn-warning btn-sm btn-accion-fecha" 
                                    data-bs-toggle="modal" data-bs-target="#modalAccionFecha" data-action="edit"
                                    data-id="'.$fecha->id_fecha.'" 
                                    data-id_ruta="'.$fecha->id_ruta.'" 
                                    data-nombre_ruta="'.($fecha->ruta->nombre_ruta ?? '').'" 
                                    data-fecha="'.$fecha->fecha.'" title="Editar">
                                    <i class="bi bi-pencil-square"></i>
                                 </button>';
                }

                if (auth()->user()->can('fechas.eliminar')) {
                    $botones .= '<button type="button" class="btn btn-danger btn-sm btn-eliminar-fecha" 
                                    data-id="'.$fecha->id_fecha.'" 
                                    data-nombre_ruta="'.($fecha->ruta->nombre_ruta ?? '').'" 
                                    data-fecha="'.$fecha->fecha.'" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                 </button>';
                }
                $botones .= '</div>';

                $data[] = [
                    $fecha->id_fecha,
                    $fecha->ruta->nombre_ruta ?? 'Sin Ruta',
                    $fecha->fecha,
                    $botones
                ];
            }

            return response()->json([
                "draw" => intval($request->input('draw')),
                "recordsTotal" => $totalRecords,
                "recordsFiltered" => $filteredRecords,
                "data" => $data
            ]);
        }

        $rutas = Ruta::all();
        return view('fechasdisponible.index', compact('rutas'));
    }



    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'fecha' => 'required|date',
        ]);

        FechaDisponible::create([
            'id_ruta' => $request->id_ruta,
            'fecha' => $request->fecha,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fecha disponible creada exitosamente.'
        ]);
    }

    public function show(FechaDisponible $fechaDisponible)
    {
        //
    }

    public function edit(FechaDisponible $fechaDisponible)
    {
        //
    }

    public function update(Request $request, FechaDisponible $fecha)
    {
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'fecha' => 'required|date',
        ]);

        $fecha->update([
            'id_ruta' => $request->id_ruta,
            'fecha' => $request->fecha,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Fecha disponible actualizada exitosamente.'
        ]);
    }

    public function destroy(FechaDisponible $fecha)
    {
        $fecha->delete();

        return response()->json([
            'success' => true,
            'message' => 'La fecha disponible ha sido eliminada correctamente.'
        ]);
    }
}
