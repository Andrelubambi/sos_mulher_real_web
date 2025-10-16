<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use App\Models\SosAlerta;

class SosAlertaCriado implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $alerta;

    public function __construct(SosAlerta $alerta)
    {
        $this->alerta = $alerta;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('sos.estagiarios');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->alerta->id,
            'user' => $this->alerta->usuario->only('id', 'name'),
            'created_at' => $this->alerta->created_at->toDateTimeString()
        ];
    }
}
