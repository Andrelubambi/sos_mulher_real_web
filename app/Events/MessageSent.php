<?php

namespace App\Events;

use App\Models\Mensagem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $mensagem;

    /**
     * Create a new event instance.
     */
    public function __construct(Mensagem $mensagem)
    {
        $this->mensagem = $mensagem;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        // CALCULAR minId e maxId AQUI, não no construtor
        $minId = min($this->mensagem->de, $this->mensagem->para);
        $maxId = max($this->mensagem->de, $this->mensagem->para);
        
        return [
            new PrivateChannel("chat.{$minId}-{$maxId}")
        ];
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'id' => $this->mensagem->id,
            'de' => $this->mensagem->de,
            'para' => $this->mensagem->para,
            'conteudo' => $this->mensagem->conteudo,
            'created_at' => $this->mensagem->created_at->toISOString(),
            'remetente' => [
                'id' => $this->mensagem->remetente->id,
                'name' => $this->mensagem->remetente->name,
            ]
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'MessageSent';
    }
}