<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Permisos agrupados con formato grupo.accion
        $permisos = [
            // Permisos para el perfil
            'perfil.ver' => 'ver el perfil',

            // Permisos para roles
            'roles.ver' => 'ver los roles',
            'roles.asignar' => 'asignar roles a los usuarios',
            'roles.crear' => 'crear nuevos roles',
            'roles.editar' => 'editar roles existentes',
            'roles.eliminar' => 'eliminar roles',

            // Permisos para permisos
            'permisos.ver' => 'ver los permisos',
            'permisos.crear' => 'crear nuevos permisos',
            'permisos.editar' => 'editar permisos existentes',
            'permisos.eliminar' => 'eliminar permisos',

            // Permisos para clientes
            'clientes.ver' => 'ver los clientes',
            'clientes.crear' => 'crear nuevos clientes',
            'clientes.editar' => 'editar clientes existentes',
            'clientes.eliminar' => 'eliminar clientes',

            // Permisos para movilidad
            'movilidad.ver' => 'ver los vehículos de movilidad',
            'movilidad.crear' => 'crear vehículos de movilidad',
            'movilidad.editar' => 'editar vehículos de movilidad',
            'movilidad.eliminar' => 'eliminar vehículos de movilidad',

            // Permisos para guías
            'guias.ver' => 'ver las guías',
            'guias.crear' => 'crear nuevas guías',
            'guias.editar' => 'editar guías existentes',
            'guias.eliminar' => 'eliminar guías',

            // Permisos para reservas
            'reservas.ver' => 'ver reservas',
            'reservas.gestionar' => 'gestionar reservas',
            'reservas.listar' => 'listar todas las reservas',
            'reservas.crear' => 'crear nuevas reservas',
            'reservas.movilidad' => 'gestionar la movilidad de reservas',
            'reservas.asignar' => 'asignar reservas a clientes',

            // Permisos para rutas
            'rutas.ver' => 'ver las rutas',
            'rutas.crear' => 'crear nuevas rutas',
            'rutas.editar' => 'editar rutas existentes',
            'rutas.eliminar' => 'eliminar rutas',

            // Permisos para lugares
            'lugares.ver' => 'ver los lugares a visitar',
            'lugares.crear' => ' crear nuevos lugares',
            'lugares.editar' => 'editar lugares existentes',
            'lugares.eliminar' => 'eliminar lugares',

            // Permisos para fechas
            'fechas.ver' => 'ver las fechas disponibles',
            'fechas.crear' => 'crear nuevas fechas',
            'fechas.editar' => 'editar fechas existentes',
            'fechas.eliminar' => 'eliminar fechas',

            // Permisos para servicios
            'servicios.ver' => 'ver los servicios',
            'servicios.crear' => 'crear nuevos servicios',
            'servicios.editar' => 'editar servicios existentes',
            'servicios.eliminar' => 'eliminar servicios',

            // Permisos para imágenes
            'imagenes.ver' => 'ver imágenes',
            'imagenes.crear' => 'crear nuevas imágenes',
            'imagenes.editar' => 'editar imágenes existentes',
            'imagenes.eliminar' => 'eliminar imágenes',

            // Permisos para detalle de rutas
            'detalleruta.ver' => 'ver los detalles de las rutas',
            'detalleruta.crear' => 'crear detalles de rutas',
            'detalleruta.editar' => 'editar detalles de rutas',
            'detalleruta.eliminar' => 'liminar detalles de rutas',

            // Permisos para pagos
            'pagos.ver' => 'ver los pagos',
            'pagos.crear' => 'crear pagos',
        ];

        // Crear permisos
        foreach ($permisos as $name => $description) {
            $permiso = Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web'],
                ['description' => $description]
            );
        }

        // Crear roles
        $admin = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
        $vendedor = Role::firstOrCreate(['name' => 'Vendedor', 'guard_name' => 'web']);
        $usuario = Role::firstOrCreate(['name' => 'Usuario', 'guard_name' => 'web']);

        // Asignar permisos a Admin (todos)
        $admin->syncPermissions(Permission::all());

        // Asignar permisos a Vendedor
        $vendedor->syncPermissions([
            'perfil.ver',
            'reservas.ver',
            'reservas.gestionar',
            'reservas.listar',
            'reservas.crear',
            'reservas.movilidad',
            'reservas.asignar',
            'clientes.ver',
            'pagos.ver',
        ]);

        // Asignar permisos a Usuario
        $usuario->syncPermissions([
            'perfil.ver',
        ]);
    }
}
