<?php
namespace App\Http\Controllers;

use App\Events\MensagemEnviada;
use App\Models\Mensagem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MensagemController extends Controller
{
    public function index($userId)
    {
        $authId = Auth::id();

        $mensagens = Mensagem::where(function ($query) use ($authId, $userId) {
            $query->where('de', $authId)->where('para', $userId);
        })->orWhere(function ($query) use ($authId, $userId) {
            $query->where('de', $userId)->where('para', $authId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($mensagens);
    }

    // Enviar nova mensagem
    public function store(Request $request)
    {
        $request->validate([
            'para' => 'required|exists:users,id',
            'conteudo' => 'required|string'
        ]);

        $mensagem = Mensagem::create([
            'de' => Auth::id(),
            'para' => $request->para,
            'conteudo' => $request->conteudo
        ]);
        return response()->json($mensagem, 201);
    }





}