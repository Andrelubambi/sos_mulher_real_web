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

Broadcast::channel('chat.{min}-{max}', function ($user, $min, $max) {
    return $user->id == $min || $user->id == $max;
});



use App\Models\Grupo;

Broadcast::channel('grupo.{grupoId}', function ($user, $grupoId){
    $grupo = Grupo::find($grupoId);
    //return $grupo && $grupo->users->contains($user->id);
    
    
    return true;
});
