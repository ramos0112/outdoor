<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:permisos.ver')->only(['index']);
        $this->middleware('can:permisos.crear')->only(['create', 'store']);
        $this->middleware('can:permisos.editar')->only(['edit', 'update']);
        $this->middleware('can:permisos.eliminar')->only(['destroy']);
    }
    public function index()
    {
        $roles = Role::with('permissions')->get();

        // Agrupa los permisos por el prefijo 
        $permisos = Permission::all()->groupBy(function ($permiso) {
            return explode('.', $permiso->name)[0];
        });

        return view('permissions.index', compact('roles', 'permisos'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        // Buscar el rol con sus permisos actuales
        $rol = Role::findOrFail($request->role_id);

        // Permisos seleccionados (si no hay ninguno, se envía un array vacío)
        $permisosSeleccionados = $request->permissions ?? [];

        // Sincronizar reemplaza todos los permisos actuales con los nuevos
        $rol->syncPermissions($permisosSeleccionados);

        return redirect()->back()->with('success', 'Permisos actualizados correctamente para el rol.');
    }

    public function destroy(string $id)
    {
        //
    }
}
