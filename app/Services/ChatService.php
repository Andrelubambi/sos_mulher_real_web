<?php

namespace App\Services;

use App\Models\User;
use App\Models\Mensagem;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function getUsuariosDisponiveis($excludeUserId)
    {
        return User::where('role', '!=', 'doutor')
            ->where('id', '!=', $excludeUserId)
            ->get();
    }

    public function getChatsRecentes($userId)
    {
        $conversas = DB::table('mensagens')
            ->selectRaw('
                CASE 
                    WHEN de = ? THEN para 
                    ELSE de 
                END as user_id,
                MAX(created_at) as ultima_data
            ', [$userId])
            ->where('de', $userId)
            ->orWhere('para', $userId)
            ->groupBy('user_id')
            ->orderByDesc('ultima_data')
            ->get();

        $chatsRecentes = [];
        foreach ($conversas as $conversa) {
            $ultimaMsg = $this->getUltimaMensagem($userId, $conversa->user_id);
            $user = User::find($conversa->user_id);
            
            if ($user && $ultimaMsg) {
                $chatsRecentes[] = [
                    'user' => $user,
                    'mensagem' => $ultimaMsg
                ];
            }
        }

        return $chatsRecentes;
    }

    private function getUltimaMensagem($userId1, $userId2)
    {
        return Mensagem::where(function ($query) use ($userId1, $userId2) {
                $query->where('de', $userId1)->where('para', $userId2)
                    ->orWhere('de', $userId2)->where('para', $userId1);
            })
            ->orderByDesc('created_at')
            ->first();
    }

    public function getMensagensEntre($userId1, $userId2)
    {
        return Mensagem::where(function ($query) use ($userId1, $userId2) {
                $query->where('de', $userId1)->where('para', $userId2);
            })
            ->orWhere(function ($query) use ($userId1, $userId2) {
                $query->where('de', $userId2)->where('para', $userId1);
            })
            ->with('remetente')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function enviarMensagem($deUserId, $paraUserId, $conteudo)
    {
        $mensagem = Mensagem::create([
            'de' => $deUserId,
            'para' => $paraUserId,
            'conteudo' => $conteudo,
        ]);

        $mensagem->load('remetente');

        // Disparar evento
        $minId = min($deUserId, $paraUserId);
        $maxId = max($deUserId, $paraUserId);
        event(new MessageSent($mensagem, $minId, $maxId));

        return $mensagem;
    }

    public function gerarCanalChat($userId1, $userId2)
    {
        $minId = min($userId1, $userId2);
        $maxId = max($userId1, $userId2);
        return "chat.{$minId}-{$maxId}";
    }
}