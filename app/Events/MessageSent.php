<?php
namespace App\Events;

use App\Models\Mensagem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log; 

class MessageSent implements ShouldBroadcast
{
    use SerializesModels;

    public $mensagem;

    public function __construct(Mensagem $mensagem)
    {
        $this->mensagem = $mensagem->load('remetente');
        Log::info('MessageSent evento disparado', [
            'remetente' => $this->mensagem->remetente,
            'conteudo' => $this->mensagem->conteudo
        ]);
    }
 
    public function broadcastOn()
    {
        $minId = min($this->mensagem->de, $this->mensagem->para);
        $maxId = max($this->mensagem->de, $this->mensagem->para);

        return new PrivateChannel("chat.{$minId}-{$maxId}");
    }

    public function broadcastWith()
    {
        
        $data = [
            'id' => $this->mensagem->id,
            'conteudo' => $this->mensagem->conteudo,
            'de' => $this->mensagem->de,
            'para' => $this->mensagem->para,
            'created_at' => $this->mensagem->created_at->toDateTimeString(),
            'remetente' => [
                'id' => $this->mensagem->remetente->id,
                'name' => $this->mensagem->remetente->name,
            ],
        ];
        Log::info('Dados para o Broadcast:', $data); 
    
        return $data;
    }
    
}
