<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación del formulario
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required|unique:clientes,numero_documento',
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|email|unique:clientes,email',
        ]);
    
        // Si no existe, crea el nuevo cliente
        Cliente::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'tipo_documento' => $request->tipo_documento,
            'numero_documento' => $request->numero_documento,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'email' => $request->email,
            'telefono' => $request->telefono,
            'pais' => $request->pais,
            'region' => $request->region,
            'ciudad' => $request->ciudad,
        ]);
    
        // Redirige con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente agregado exitosamente.');
    }
    
    
    
    

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        // Validación con exclusión del cliente actual en los campos de número de documento y email
        $request->validate([
            'nombre' => 'required',
            'apellido' => 'required',
            'tipo_documento' => 'required',
            'numero_documento' => 'required|unique:clientes,numero_documento,' . $cliente->id_cliente . ',id_cliente', // Validación con el ID del cliente para permitir el mismo valor
            'fecha_nacimiento' => 'required|date',
            'email' => 'required|email|unique:clientes,email,' . $cliente->id_cliente . ',id_cliente', // Lo mismo para el email
            'telefono' => 'nullable',
            'pais' => 'nullable',
            'region' => 'nullable',
            'ciudad' => 'nullable',
        ]);
    
        // Si pasa la validación, actualizamos el cliente
        $cliente->update($request->all());
    
        // Redirige a la misma página con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente');
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_cliente)
    {
        // Obtener el cliente que se desea eliminar
        $cliente = Cliente::findOrFail($id_cliente);
    
        // Eliminar el cliente
        $cliente->delete();
    
        // Redirigir a la vista con un mensaje de éxito
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente');
    }
    
}
