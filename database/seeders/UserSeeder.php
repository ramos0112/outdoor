<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        $admin = User::updateOrCreate(
            ['email' => 'ayniforestperu@gmail.com'],
            [
                'name' => 'Admin',
                'email_verified_at' => now(),
                'password' => Hash::make('Ayni_2026-Fores7'),
            ]
        );

        $admin->assignRole('Admin');

        // Vendedor
        $vendedor = User::updateOrCreate(
            ['email' => 'Aynivendedor@gmail.com'],
            [
                'name' => 'vendedor',
                'email_verified_at' => now(),
                'password' => Hash::make('20.Vendedor.26'),
            ]
        );

        $vendedor->assignRole('Vendedor');

        // Usuario
        $usuario = User::updateOrCreate(
            ['email' => 'usuario@gmail.com'],
            [
                'name' => 'usuario',
                'email_verified_at' => now(),
                'password' => Hash::make('Usuario153'),
            ]
        );

        $usuario->assignRole('Usuario');
    }
}