<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'teste@example.com'],
            [
                'name' => 'Usuário de Teste',
                'password' => Hash::make('password'),
            ]
        );

        // Criar token Sanctum
        $token = $user->createToken('token-de-teste')->plainTextToken;

        // Mostrar no console
        echo "\nToken de teste gerado:\n";
        echo "Bearer {$token}\n";
    }
}

