<?php
// routes/channels.php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Aqui você pode registrar todos os canais de broadcast para seu app.
| O callback recebe o usuário autenticado e deve retornar true/false.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

/**
 * Canal de chat privado entre dois usuários
 * Formato: chat.{minId}-{maxId}
 * 
 * Exemplo: chat.5-10 (usuários 5 e 10)
 */
Broadcast::channel('chat.{minId}-{maxId}', function ($user, $minId, $maxId) {
    $id1 = (int) $minId;
    $id2 = (int) $maxId;
    $userId = (int) $user->id;
    
    // Verificar se o usuário autenticado é um dos participantes
    $isAuthorized = ($userId === $id1 || $userId === $id2);
    
    Log::info('🔐 [BROADCAST] Autenticação de canal privado', [
        'canal' => "chat.{$minId}-{$maxId}",
        'usuario_autenticado' => $userId,
        'autorizado' => $isAuthorized ? 'SIM ✅' : 'NÃO ❌'
    ]);
    
    return $isAuthorized;
});

/**
 * Canal SOS (público ou restrito)
 */
Broadcast::channel('mensagem_sos', function ($user) {
    // Permitir acesso apenas para doutores e admins
    $allowed = in_array($user->role, ['admin', 'doutor', 'estagiario']);
    
    Log::info('🚨 [BROADCAST] Autenticação canal SOS', [
        'usuario' => $user->id,
        'role' => $user->role,
        'autorizado' => $allowed ? 'SIM ✅' : 'NÃO ❌'
    ]);
    
    return $allowed;
});


 