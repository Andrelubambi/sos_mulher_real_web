<?php
// ARQUIVO TEMPORÁRIO - DELETAR APÓS USO!
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

try {
    // Criar usuário Admin
    $admin = User::firstOrCreate(
        ['telefone' => '999888777'],  
        [
            'name' => 'Administrador',
            'email' => 'admin@sistema.com',
            'password' => Hash::make('Admin@123'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]
    );

    // Criar usuário Estagiário
    $estagiario = User::firstOrCreate(
        ['telefone' => '999888666'],  
        [
            'name' => 'Estagiário',
            'email' => 'estagiario@sistema.com',
            'password' => Hash::make('Estagiario@123'),
            'role' => 'estagiario',
            'email_verified_at' => now(),
        ]
    );

    // Criar usuário Doutor
    $doutor = User::firstOrCreate(
        ['telefone' => '999888555'],  
        [
            'name' => 'Dr. João Silva',
            'email' => 'doutor@sistema.com',
            'password' => Hash::make('Doutor@123'),
            'role' => 'doutor',
            'email_verified_at' => now(),
        ]
    );

    echo "<h2>✅ Usuários criados com sucesso!</h2>";
    echo "<table border='1' style='border-collapse: collapse; padding: 10px;'>";
    echo "<tr><th>Nome</th><th>Email</th><th>Telefone</th><th>Role</th><th>Senha</th></tr>";
    echo "<tr><td>Administrador</td><td>admin@sistema.com</td><td>999888777</td><td>admin</td><td>Admin@123</td></tr>";
    echo "<tr><td>Estagiário</td><td>estagiario@sistema.com</td><td>999888666</td><td>estagiario</td><td>Estagiario@123</td></tr>";
    echo "<tr><td>Dr. João Silva</td><td>doutor@sistema.com</td><td>999888555</td><td>doutor</td><td>Doutor@123</td></tr>";
    echo "</table>";
    
    echo "<p style='color: red; font-weight: bold;'>⚠️ IMPORTANTE: DELETE este arquivo após o uso!</p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Erro:</h2>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>