<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mensagem;
use App\Events\MessageSent;

use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function showChatIndex()
    {
        $usuario = auth()->user();

        $messages = Mensagem::with('remetente')
            ->where('de', $usuario->id)
            ->orWhere('para', $usuario->id)
            ->orderBy('created_at', 'asc')
            ->get();
    
        $usuariosNaoDoutores = User::where('id', '!=', $usuario->id)
            ->where('role', '!=', 'doutor')
            ->get();
    
        return view('chat', compact('usuario', 'usuariosNaoDoutores', 'messages'));
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

    $request->validate([
        'conteudo' => 'required|string|max:1000',
    ]);

    $usuario = User::find($usuarioId);
    if (!$usuario) {
        return redirect()->back()->withErrors('Usuário não encontrado');
    }
 
    $mensagem = Mensagem::create([
        'de' => auth()->user()->id,  
        'para' => $usuario->id,      
        'conteudo' => $request->conteudo,  
    ]);
 
    event(new MessageSent($mensagem));  
 
    return redirect()->route('chat', ['usuarioId' => $usuarioId]);
}
}
