<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mensagem;
use App\Events\MessageSent;

use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function index()
    {
        $usuariosNaoDoutores = User::where('role', '!=', 'doutor')->
        where('id', '!=', auth()->user()->id)
        ->get();

        return view('chat', compact('usuariosNaoDoutores'));
    }

    public function getMessages($usuarioId)
{
    $usuario = User::find($usuarioId);

    if (!$usuario) {
        return response()->json(['error' => 'Usuário não encontrado'], 404);
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
        ->with('remetente') 
        ->orderBy('created_at', 'asc')
        ->get();

    return response()->json($messages);
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
 
    //return redirect()->route('chat', ['usuarioId' => $usuarioId]);
    return response()->json($mensagem);
    }


public function sendToInterns(Request $request)
{
    $request->validate([
        'conteudo' => 'required|string|max:1000',
    ]);

    $estagiarios = User::where('role', 'estagiario')->get();

    foreach ($estagiarios as $estagiario) {
        $mensagem = Mensagem::create([
            'de' => auth()->id(),
            'para' => $estagiario->id,
            'conteudo' => $request->conteudo,
        ]);

        event(new MessageSent($mensagem));
    }

    return response()->json(['success' => true, 'message' => 'Mensagem enviada para todos os estagiários.']);
}
}
