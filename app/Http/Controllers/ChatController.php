<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mensagem;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function showChat($usuarioId)
{
    $usuario = User::find($usuarioId);
    $messages = Mensagem::where(function($query) use ($usuarioId) {
        $query->where('de', auth()->user()->id)->where('para', $usuarioId);
    })
    ->orWhere(function($query) use ($usuarioId) {
        $query->where('de', $usuarioId)->where('para', auth()->user()->id);
    })
    ->get();

    return view('chat', compact('usuario', 'messages'));
}



public function showChatWithUser($usuarioId)
{
   
    $usuario = User::find($usuarioId);

    
    if (!$usuario) {
        abort(404, 'Usuário não encontrado');
    } 
    $usuarioLogado = auth()->user();  
    $messages = Mensagem::where(function ($query) use ($usuarioLogado, $usuario) {
            $query->where('de', $usuarioLogado->id)
                  ->where('para', $usuario->id);
        })
        ->orWhere(function ($query) use ($usuarioLogado, $usuario) {
            $query->where('de', $usuario->id)
                  ->where('para', $usuarioLogado->id);
        })
        ->orderBy('created_at', 'asc') 
        ->get();
 
    return view('chat', compact('usuario', 'messages'));
}


public function sendMessage(Request $request, $usuarioId)
{
    // Validação da mensagem
    $request->validate([
        'conteudo' => 'required|string|max:1000', // Validação do conteúdo da mensagem
    ]);

    // Encontrar o usuário destinatário
    $usuario = User::find($usuarioId);

    // Verificar se o usuário existe
    if (!$usuario) {
        return redirect()->back()->withErrors('Usuário não encontrado');
    }
 
    Mensagem::create([
        'de' => auth()->user()->id,  
        'para' => $usuario->id,      
        'conteudo' => $request->conteudo,  
    ]);
 
    return redirect()->route('chat.withUser', ['usuarioId' => $usuarioId]);
}
}
