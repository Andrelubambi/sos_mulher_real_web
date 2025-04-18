<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
namespace App\Events;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

 

use App\Models\Mensagem;

class MensagemEnviada implements ShouldBroadcast
{
    public $mensagem;

    public function __construct(Mensagem $mensagem)
    {
        $this->mensagem = $mensagem;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->mensagem->para);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->mensagem->id,
            'de' => $this->mensagem->de,
            'para' => $this->mensagem->para,
            'conteudo' => $this->mensagem->conteudo,
            'created_at' => $this->mensagem->created_at->toDateTimeString()
        ];
    }
}
