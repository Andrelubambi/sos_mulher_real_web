<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mensagem;
use App\Models\MensagemSos;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Auth;
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
            ', [$userId])
            ->where('de', $userId)
            ->orWhere('para', $userId)
            ->groupBy('user_id')
            ->orderByDesc('ultima_data')
            ->get();

        // Obter os dados do usuário com quem ele conversou
        $chatsRecentes = [];
        foreach ($conversas as $conversa) {
            $ultimoMsg = Mensagem::where(function ($query) use ($userId, $conversa) {
                    $query->where('de', $userId)->where('para', $conversa->user_id)
                        ->orWhere('de', $conversa->user_id)->where('para', $userId);
                })
                ->orderByDesc('created_at')
                ->first();

            $user = User::find($conversa->user_id);

            // Contar mensagens não lidas
            $unreadCount = Mensagem::where('para', $userId)
                ->where('de', $conversa->user_id)
                ->whereNull('read_at') // Assumindo que há uma coluna read_at
                ->count();

            if ($user && $ultimoMsg) {
                $chatsRecentes[] = [
                    'user' => $user,
                    'mensagem' => $ultimoMsg,
                    'unread_count' => $unreadCount
                ];
            }
        }

        return view('chat.index', compact('usuariosNaoDoutores', 'chatsRecentes'));
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

        // Marcar mensagens como lidas
        Mensagem::where('para', $usuarioLogado->id)
            ->where('de', $usuario->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, $usuarioId)
{
    $request->validate([
        'conteudo' => 'required|string',
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

    // Carregar o relacionamento remetente
    $mensagem->load('remetente');

    // Disparar evento - APENAS a mensagem, o canal é calculado no evento
    broadcast(new MessageSent($mensagem))->toOthers();

    return response()->json($mensagem);
}

public function responderMensagemSos($id)
{
    try { 
        $mensagemSos = MensagemSos::findOrFail($id);
         
        if ($mensagemSos->status !== 'lido') {
             $mensagemSos->status = 'lido';
             $mensagemSos->save(); 
        }
         
        $usuarioDeInteresse = User::find($mensagemSos->enviado_por);

        if (!$usuarioDeInteresse) {
            return redirect()->route('doutor.dashboard')->with('error', 'Remetente do SOS não encontrado.');
        }
 
        return view('chat.index', [
            'initialChatUserId' => $usuarioDeInteresse->id, 
            'chatWithUser' => $usuarioDeInteresse,          
            'usuariosNaoDoutores' => User::where('role', '!=', 'doutor')->get(),
            'chatsRecentes' => app(\App\Http\Controllers\ChatController::class)->index()->getData()['chatsRecentes']
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return redirect()->back()->with('error', 'Mensagem SOS não encontrada.');
    } catch (\Exception $e) {
        // Logar o erro real para debug
        \Log::error("Erro ao responder SOS {$id}: " . $e->getMessage()); 
        return redirect()->back()->with('error', 'Ocorreu um erro ao iniciar a resposta.');
    }
}

}