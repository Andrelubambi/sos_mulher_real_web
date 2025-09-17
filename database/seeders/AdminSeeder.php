<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    { 
        // Criar usuário Admin
        User::firstOrCreate(
            ['telefone' => '999888777'],  
            [
                'name' => 'Administrador',
                 
                'password' => Hash::make('Admin@123'),
                'role' => 'admin',
                 
            ]
        );

        // Criar usuário Estagiário
        User::firstOrCreate(
            ['telefone' => '999888666'],  
            [
                'name' => 'Estagiário',
                 
                'password' => Hash::make('Estagiario@123'),
                'role' => 'estagiario',
                 
            ]
        );

        // Criar usuário Doutor
        User::firstOrCreate(
            ['telefone' => '999888555'],  
            [
                'name' => 'Dr. João Silva',
                
                'password' => Hash::make('Doutor@123'),
                'role' => 'doutor',
                 
            ]
        );

        $this->command->info('✅ Usuários criados com sucesso!');
    }
}