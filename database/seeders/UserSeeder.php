<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->withPersonalTeam()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'email_verified_at' => now(), // Verificado
            'password' => Hash::make('password123'), // Contraseña
        ]);

        // Puedes crear más usuarios si quieres:
        User::factory(5)->withPersonalTeam()->create([
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
        ]);
    }
}