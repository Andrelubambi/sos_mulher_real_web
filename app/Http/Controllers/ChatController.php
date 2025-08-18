<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mensagem;
use App\Models\MensagemSos;
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

    public function responderMensagemSos($id)
    {
        $userId = auth()->id();
        $mensagemSos = MensagemSos::findOrFail($id);

        $remetenteId = $mensagemSos->enviado_por; 


        $mensagem = Mensagem::create([
            'de' => $remetenteId,
            'para' => $userId,
            'conteudo' => $mensagemSos->conteudo,
        ]);

        // Busca o histórico de mensagens entre os dois usuários
        $mensagens = Mensagem::where(function ($query) use ($userId, $remetenteId) {
            $query->where('de', $userId)->where('para', $remetenteId);
        })->orWhere(function ($query) use ($userId, $remetenteId) {
            $query->where('de', $remetenteId)->where('para', $userId);
        })->with('remetente')->orderBy('created_at')->get();

        $mensagemSos->status = 'lido';
        $mensagemSos->save();

        // Passa os dados para a view
        return view('chat_2', [
            'mensagens' => $mensagens,
            'remetente' => User::find($mensagemSos->enviado_por),
        ]);
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


    public function sendMessage(Request $request, $usuarioId)   {
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


    $minId = min(auth()->user()->id, $usuario->id);
        $maxId = max(auth()->user()->id, $usuario->id);
        
        event(new MessageSent($mensagem, $minId, $maxId));
 
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
