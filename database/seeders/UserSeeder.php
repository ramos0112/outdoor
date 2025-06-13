<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear el usuario admin y asignarle el rol Administrador
        $admin = User::factory()->withPersonalTeam()->create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
        ]);

        // Asignar rol Administrador (tiene todos los permisos)
        $admin->assignRole('Admin');

        // Crear un usuario con rol Vendedor
        $vendedor = User::factory()->withPersonalTeam()->create([
            'name' => 'vendedor',
            'email' => 'vendedor@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('vendedor123'),
        ]);

        $vendedor->assignRole('Vendedor');

        // Crear un usuario con rol Usuario
        $usuario = User::factory()->withPersonalTeam()->create([
            'name' => 'usuario',
            'email' => 'usuario@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('usuario123'),
        ]);

        $usuario->assignRole('Usuario');
    }
}
