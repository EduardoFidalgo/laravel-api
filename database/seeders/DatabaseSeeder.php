<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Criação do usuário administrador
        User::create([
            'name' => 'Administrador',
            'email' => 'administrador@email.com',
            'password' => Hash::make('w0rk0ut'),
        ]);
    }
}
