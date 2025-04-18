<?php
namespace App\Http\Controllers;

use App\Events\MensagemEnviada;
use App\Models\Mensagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MensagemController extends Controller
{
    public function enviar(Request $request)
    {
        $request->validate([
            'para' => 'required|exists:users,id',
            'conteudo' => 'required|string',
        ]);

        $mensagem = Mensagem::create([
            'de' => auth()->id(),
            'para' => $request->para,
            'conteudo' => $request->conteudo,
        ]);

        broadcast(new MensagemEnviada($mensagem))->toOthers();

        return response()->json($mensagem, 201);
    }

    public function listar($userId)
    {
        $userId = (int) $userId;

        $mensagens = Mensagem::where(function ($q) use ($userId) {
            $q->where('de', auth()->id())
              ->where('para', $userId);
        })->orWhere(function ($q) use ($userId) {
            $q->where('de', $userId)
              ->where('para', auth()->id());
        })->orderBy('created_at')->get();

        return response()->json($mensagens);
    }
}