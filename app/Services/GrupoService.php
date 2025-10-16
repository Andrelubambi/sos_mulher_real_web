<?php

namespace App\Services;

use App\Models\Grupo;
use App\Models\User;
use App\Models\MensagemGrupo;
use App\Events\GroupMessageSent;

class GrupoService
{
    public function getAllGrupos()
    {
        return Grupo::with('admin')->get();
    }

    public function getUsuariosDisponiveis($excludeUserId)
    {
        return User::where('id', '!=', $excludeUserId)->get();
    }

    public function criarGrupo($nome, $descricao, $adminId)
    {
        return Grupo::create([
            'nome' => $nome,
            'descricao' => $descricao,
            'admin_id' => $adminId,
        ]);
    }

    public function getMensagensDoGrupo($grupoId)
    {
        return MensagemGrupo::where('grupo_id', $grupoId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function enviarMensagemGrupo($grupoId, $userId, $conteudo)
    {
        $mensagem = MensagemGrupo::create([
            'grupo_id' => $grupoId,
            'user_id' => $userId,
            'conteudo' => $conteudo,
        ]);

        event(new GroupMessageSent($mensagem));

        return $mensagem;
    }

    public function adicionarUsuarioAoGrupo($grupo, $userId)
    {
        return $grupo->users()->attach($userId);
    }

    public function removerUsuarioDoGrupo($grupo, $userId)
    {
        return $grupo->users()->detach($userId);
    }

    public function podeGerenciarGrupo($grupo, $userId)
    {
        return $grupo->admin_id === $userId;
    }

    public function excluirGrupo($grupo, $userId)
    {
        if (!$this->podeGerenciarGrupo($grupo, $userId)) {
            throw new \Exception('Apenas o administrador pode excluir o grupo.');
        }

        return $grupo->delete();
    }
}