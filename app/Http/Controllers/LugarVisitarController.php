<?php

namespace App\Http\Controllers;

use App\Models\LugarVisitar;
use App\Services\LugarVisitarDataTableService;
use App\Models\Ruta;
use Illuminate\Http\Request;

class LugarVisitarController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:lugares.ver')->only(['index', 'show']);
        $this->middleware('can:lugares.crear')->only(['create', 'store']);
        $this->middleware('can:lugares.editar')->only(['edit', 'update']);
        $this->middleware('can:lugares.eliminar')->only(['destroy']);
    }
    public function index(Request $request, LugarVisitarDataTableService $dataTableService)
    {
        if ($request->ajax()) {
            return response()->json($dataTableService->procesar($request));
        }

        $rutas = Ruta::all(); // Necesario para el modal de 'crear'
        return view('lugaresvisitar.index', compact('rutas'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'nombre_lugar' => 'required|string|max:255',
        ]);

        $lugares = LugarVisitar::create([
            'id_ruta' => $request->id_ruta,
            'nombre_lugar' => $request->nombre_lugar,
        ]);

        return redirect()->route('lugares.index')->with('success', 'Lugar creado exitosamente');
    }

    public function show(LugarVisitar $lugarVisitar)
    {
        //
    }

    public function edit($id)
    {
        $lugar = LugarVisitar::findOrFail($id); // Buscar el lugar por su ID
        $rutas = Ruta::all(); // Obtener todas las rutas disponibles
        return view('lugaresvisitar.edit', compact('lugar', 'rutas')); // Retornar la vista con el lugar y las rutas
    }

    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'id_ruta' => 'required|exists:rutas,id_ruta',
            'nombre_lugar' => 'required|string|max:255',
        ]);

        // Buscar el lugar y actualizarlo
        $lugar = LugarVisitar::findOrFail($id);
        $lugar->update([
            'id_ruta' => $request->id_ruta,
            'nombre_lugar' => $request->nombre_lugar,
        ]);

        // Redirigir con mensaje de éxito
        return redirect()->route('lugares.index')->with('success', 'Lugar actualizado exitosamente');
    }

    public function destroy($id)
    {
        $lugar = LugarVisitar::findOrFail($id);
        $lugar->delete();

        return redirect()->route('lugares.index')->with('success', 'Lugar eliminado correctamente');
    }
}
