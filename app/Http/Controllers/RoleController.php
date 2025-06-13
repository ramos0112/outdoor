<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



class RoleController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:roles.ver')->only(['index']);
        $this->middleware('can:roles.crear')->only(['create', 'store']);
        $this->middleware('can:roles.asignar')->only(['update']);
        $this->middleware('can:roles.eliminar')->only(['destroy']);
    }

    public function index()
    {
        $users = User::all(); // Obtener todos los usuarios
        $roles = Role::all(); // Obtener todos los roles
        return view('roles.index', compact('users', 'roles'));
    }
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles|max:255',
        ]);

        Role::create([
            'name' => $request->name,
            'guard_name' => 'web', // <- MUY IMPORTANTE
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol creado exitosamente');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, $userId)
    {
        $user = User::findOrFail($userId);

        // Validación si el rol es seleccionado
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // Verificar si el usuario autenticado está intentando cambiarse su propio rol
        if (Auth::id() === $user->id) {

            return redirect()->back()->withErrors([
                'error' => 'No puedes cambiar tu propio rol desde esta vista por seguridad.'
            ]);
        }

        // Asignar el nuevo rol al usuario
        $role = Role::findOrFail($request->role_id);
        $user->syncRoles([$role]);

        return redirect()->route('roles.index')->with('success', 'Rol asignado correctamente');
    }

    public function destroy(string $id)
    {
        //
    }
}
