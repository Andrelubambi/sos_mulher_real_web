<?php
namespace App\Events;

use App\Models\MensagemGrupo;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class GroupMessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $mensagem;

    /**
     * Create a new event instance.
     */
    public function __construct(MensagemGrupo $mensagem)
    {
        Log::info('Recebendo evento GroupMessageSent');
        $this->mensagem = $mensagem;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn()
    {
        return new Channel('grupo.' . $this->mensagem->grupo_id);
    }

    /**
     * The data to broadcast.
     */
    public function broadcastWith()
{
    return [
        'id' => $this->mensagem->id,
        'grupo_id' => $this->mensagem->grupo_id,
        'user_id' => $this->mensagem->user_id,
        'conteudo' => $this->mensagem->conteudo,
        'created_at' => $this->mensagem->created_at->toDateTimeString(),
        'user' => [
            'id' => $this->mensagem->user->id,
            'name' => $this->mensagem->user->name,
        ],
    ];
}

 public function broadcastAs()
    {
        return 'GroupMessageSent';
    }

}