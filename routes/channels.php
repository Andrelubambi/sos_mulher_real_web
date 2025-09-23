<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Canal para chat privado entre dois usuários
Broadcast::channel('chat.{minId}-{maxId}', function ($user, $minId, $maxId) {
    // Verificar se o usuário autenticado é um dos participantes do chat
    $userId = $user->id;
    return $userId == $minId || $userId == $maxId;
});


Broadcast::channel('test-channel', function ($user) {
    return true; // Todos os usuários autenticados podem acessar
});