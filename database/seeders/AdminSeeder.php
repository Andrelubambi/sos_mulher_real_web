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
        // Cria um utilizador com a role 'admin'
        User::create([
            'name' => 'Admin User',
            'telefone' => '953478961',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);
    }
}
