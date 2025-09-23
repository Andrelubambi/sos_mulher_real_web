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
    public $minId;
    public $maxId;

    /**
     * Create a new event instance.
     */
    public function __construct(Mensagem $mensagem, $minId, $maxId)
    {
        $this->mensagem = $mensagem;
        $this->minId = $minId;
        $this->maxId = $maxId;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("chat.{$this->minId}-{$this->maxId}")
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
            'created_at' => $this->mensagem->created_at,
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