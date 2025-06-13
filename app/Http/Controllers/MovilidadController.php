<?php

namespace App\Http\Controllers;

use App\Models\Movilidad;
use App\Models\Guia;
use Illuminate\Http\Request;

class MovilidadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:movilidad.ver')->only(['index', 'show']);
        $this->middleware('can:movilidad.crear')->only(['create', 'store']);
        $this->middleware('can:movilidad.editar')->only(['edit', 'update']);
        $this->middleware('can:movilidad.eliminar')->only(['destroy']);
    }
    public function index()
    {
        //
        $movilidades = Movilidad::all();
        $guias = Guia::all();
        return view('movilidades.index', compact('movilidades', 'guias'));
    }

    public function create()
    {
        //
    }
    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'ruta' => 'required|string',
            'tipo_movilidad' => 'required|string',
            'capacidad' => 'required|integer',
            'estado' => 'required|in:Disponible,Ocupado',
/*             'id_guia' => 'required|array', // Asegurarse de que se seleccionen guías
 */            'id_guia.*' => 'exists:guias,id_guia' // Verifica que los guías seleccionados existan
        ]);

        // Crear la nueva movilidad
        $movilidad = Movilidad::create([
            'ruta' => $request->ruta,
            'tipo_movilidad' => $request->tipo_movilidad,
            'capacidad' => $request->capacidad,
            'estado' => $request->estado,
        ]);

        // Asignar guías a la movilidad
        $movilidad->guias()->sync($request->id_guia);

        return redirect()->route('movilidades.index')->with('success', 'Movilidad creada y guías asignados correctamente');
    }

    public function show(Movilidad $movilidad)
    {
        //
    }

    public function edit(Movilidad $movilidad)
    {
        //
    }

    public function update(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'ruta' => 'required|string',
            'tipo_movilidad' => 'required|string',
            'capacidad' => 'required|integer',
            'estado' => 'required|in:Disponible,Ocupado',
            'guias' => 'required|array',
            'guias.*' => 'exists:guias,id_guia',
        ]);

        // Obtener la movilidad y actualizarla
        $movilidad = Movilidad::findOrFail($id);
        $movilidad->update([
            'capacidad' => $request->capacidad,
            'estado' => $request->estado,
        ]);

        // Sincronizar los guías seleccionados con la movilidad
        $movilidad->guias()->sync($request->guias);

        return redirect()->route('movilidades.index')->with('success', 'Movilidad actualizada correctamente');
    }

    public function destroy($id)
    {
        $movilidad = Movilidad::findOrFail($id);
        $movilidad->delete(); // Eliminar la movilidad

        return redirect()->route('movilidades.index')->with('success', 'Movilidad eliminada correctamente');
    }
}
