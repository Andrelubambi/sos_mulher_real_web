<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mensagem;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ChatController extends Controller
{

    public function index()
{
    $userId = auth()->id();

    // Usuários que não são doutores e não é o usuário logado
    $usuariosNaoDoutores = User::where('role', '!=', 'doutor')
        ->where('id', '!=', $userId)
        ->get();

    $conversas = DB::table('mensagens')
        ->selectRaw('
            CASE 
                WHEN de = ? THEN para 
                ELSE de 
            END as user_id,
            MAX(created_at) as ultima_data,
            MAX(conteudo) as ultima_mensagem
        ', [$userId]) // <-- aqui o parâmetro é passado corretamente
        ->where('de', $userId)
        ->orWhere('para', $userId)
        ->groupBy('user_id')
        ->orderByDesc('ultima_data')
        ->get();

    // Obter os dados do usuário com quem ele conversou
    $chatsRecentes = [];
    foreach ($conversas as $conversa) {
        $ultimoMsg = \App\Models\Mensagem::where(function ($query) use ($userId, $conversa) {
                $query->where('de', $userId)->where('para', $conversa->user_id)
                      ->orWhere('de', $conversa->user_id)->where('para', $userId);
            })
            ->orderByDesc('created_at')
            ->first();

        $user = \App\Models\User::find($conversa->user_id);
        if ($user && $ultimoMsg) {
            $chatsRecentes[] = [
                'user' => $user,
                'mensagem' => $ultimoMsg
            ];
        }
    }

    return view('chat', compact('usuariosNaoDoutores', 'chatsRecentes'))->with('mensagens', $chatsRecentes);

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
        return response()->json(['error' => 'Usuário não encontrado'], 404);
    }

    $mensagem = Mensagem::create([
        'de' => auth()->user()->id,  
        'para' => $usuario->id,      
        'conteudo' => $request->conteudo,  
    ]);
 
    event(new MessageSent($mensagem));  
 
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
