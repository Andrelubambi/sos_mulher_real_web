<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Mensagem;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    // A solução: declare a propriedade aqui
    public Mensagem $message;

    public function __construct(Mensagem $message)
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        $minId = min($this->message->de, $this->message->para);
        $maxId = max($this->message->de, $this->message->para);
        $channel = "chat.{$minId}-{$maxId}";
        
        \Log::info("📡 Broadcasting para Redis canal: {$channel}");
        
        return new PrivateChannel($channel);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->message->id,
            'de' => $this->message->de,
            'para' => $this->message->para,
            'conteudo' => $this->message->conteudo,
            'created_at' => $this->message->created_at,
            'channel' => "chat.{$this->getMinId()}-{$this->getMaxId()}",
            'remetente' => $this->message->remetente ? [
                'id' => $this->message->remetente->id,
                'name' => $this->message->remetente->name,
            ] : null
        ];
    }

    public function broadcastAs()
    {
        return 'MessageSent';
    }

    private function getMinId()
    {
        return min($this->message->de, $this->message->para);
    }

    private function getMaxId()
    {
        return max($this->message->de, $this->message->para);
    }
}