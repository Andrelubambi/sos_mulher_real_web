<?php

namespace App\Services;

use App\Models\User;
use App\Models\MensagemSos;
use App\Events\MensagemSosEvent;

class SosService
{
    public function enviarMensagemParaEstagiarios($conteudo, $enviadoPorId)
    {
        $estagiarios = User::where('role', 'estagiario')->get();
        $mensagensEnviadas = [];

        foreach ($estagiarios as $estagiario) {
            $mensagem = MensagemSos::create([
                'user_id' => $estagiario->id,
                'conteudo' => $conteudo,
                'enviado_por' => $enviadoPorId
            ]);

            event(new MensagemSosEvent($mensagem));
            $mensagensEnviadas[] = $mensagem;
        }

        return $mensagensEnviadas;
    }

    public function marcarComoLida($mensagemId)
    {
        $mensagem = MensagemSos::findOrFail($mensagemId);
        $mensagem->update(['status' => 'lido']);
        
        return $mensagem;
    }

    public function getMensagensNaoLidas($userId)
    {
        return MensagemSos::where('user_id', $userId)
            ->where('status', 'nao_lido')
            ->orderBy('created_at', 'desc')
            ->get(['id', 'conteudo', 'created_at as data']);
    }

    public function getMensagemParaResposta($mensagemId)
    {
        return MensagemSos::findOrFail($mensagemId);
    }
}