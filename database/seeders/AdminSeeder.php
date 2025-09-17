<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    { 
        // Usuário Admin
        User::updateOrCreate(
            ['telefone' => '999990000'],
            [
                'name' => 'Admin Master',
                'telefone' => '999990000',
                'password' => Hash::make('Master@2024'),
                'role' => 'admin',
            ]
        );

        // Usuário Estagiário
        User::updateOrCreate(
            ['telefone' => '999992222'],
            [
                'name' => 'Carlos Estagiário',
                'telefone' => '999992222',
                'password' => Hash::make('Estagio@2024'),
                'role' => 'estagiario',
            ]
        );

        // Usuário Doutor 1
        User::updateOrCreate(
            ['telefone' => '999994444'],
            [
                'name' => 'Dra. Maria Santos',
                'telefone' => '999994444',
                'password' => Hash::make('DraMaria@2024'),
                'role' => 'doutor',
            ]
        );

        // Usuário Doutor 2
        User::updateOrCreate(
            ['telefone' => '999995555'],
            [
                'name' => 'Dr. Pedro Costa',
                'telefone' => '999995555',
                'password' => Hash::make('DrPedro@2024'),
                'role' => 'doutor',
            ]
        );

        // Usuário Vítima
        User::updateOrCreate(
            ['telefone' => '999996666'],
            [
                'name' => 'João Silva',
                'telefone' => '999996666',
                'password' => Hash::make('Vitima@2024'),
                'role' => 'vitima',
            ]
        );

        $this->command->info('✅ Usuários criados/atualizados com sucesso!');
    }
}