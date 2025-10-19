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
            ['email' => 'andrelubambi36@gmail.com'], // ✅ Mudado para email
            [
                'name' => 'André Lubambi',
                'email' => 'andrelubambi36@gmail.com', // ✅ Adicionado
                'telefone' => '999999990',
                'password' => Hash::make('Admin@2025'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate( 
            ['email' => 'andre.d.lubambi@gmail.com'],  
            [
                'name' => 'André Lubambi',
                'email' => 'andre.d.lubambi@gmail.com', / 
                'telefone' => '999999999',
                'password' => Hash::make('Admin@2025'),
                'role' => 'admin',
            ]
        );

        User::updateOrCreate( 
            ['email' => 'admin@master.com'], // ✅ Mudado para email
            [
                'name' => 'Admin Master',
                'email' => 'admin@master.com', // ✅ Adicionado
                'telefone' => '999990000',
                'password' => Hash::make('Master@2024'),
                'role' => 'admin',
            ]
        );

        // Usuário Estagiário
        User::updateOrCreate(
            ['email' => 'carlos@estagiario.com'], // ✅ Mudado para email
            [
                'name' => 'Carlos Estagiário',
                'email' => 'carlos@estagiario.com', // ✅ Adicionado
                'telefone' => '999992222',
                'password' => Hash::make('Estagio@2024'),
                'role' => 'estagiario',
            ]
        );  

        // Usuário Doutor 1
        User::updateOrCreate(
            ['email' => 'maria@doutor.com'], // ✅ Mudado para email
            [
                'name' => 'Dra. Maria Santos',
                'email' => 'maria@doutor.com', // ✅ Adicionado
                'telefone' => '999994444',
                'password' => Hash::make('DraMaria@2024'),
                'role' => 'doutor',
            ]
        );

        // Usuário Doutor 2 
        User::updateOrCreate(
            ['email' => 'pedro@doutor.com'], // ✅ Mudado para email
            [
                'name' => 'Dr. Pedro Costa',
                'email' => 'pedro@doutor.com', // ✅ Adicionado
                'telefone' => '999995555',
                'password' => Hash::make('DrPedro@2024'),
                'role' => 'doutor',
            ]
        );

        // Usuário Vítima
        User::updateOrCreate(
            ['email' => 'joao@vitima.com'], // ✅ Mudado para email
            [
                'name' => 'João Silva',
                'email' => 'joao@vitima.com', // ✅ Adicionado
                'telefone' => '999996666',
                'password' => Hash::make('Vitima@2024'),
                'role' => 'vitima',
            ]
        );

        $this->command->info('✅ Usuários criados/atualizados com sucesso!');
    }
}