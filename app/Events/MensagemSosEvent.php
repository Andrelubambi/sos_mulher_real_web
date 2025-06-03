<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\MensagemSos;

class MensagemSosEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public MensagemSos $mensagem;
    public function __construct($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new Channel('mensagem_sos')];
    }

    public function broadcastWith():array{
        return [
            'user_id' => $this->mensagem->user_id,
            'conteudo'=> $this->mensagem->conteudo,
            'data'=>$this->mensagem->created_at,
            'id' => $this->mensagem->id
        ];
    }
    public function broadcastAs(){
        return 'NovaMensagemSosEvent';
    }
}
