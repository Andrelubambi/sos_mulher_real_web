<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MensagemSos;
use App\Models\User;
use App\Events\MensagemSosEvent;

class MensagemSosController extends Controller
{
    public function enviarMensagemSos(Request $request){
        $users_estagiarios = User::where('role','estagiario')->get();
        foreach($users_estagiarios  as $user){
            $mensagem = MensagemSos::create([
                'user_id' => $user->id,
                'conteudo'=> $request->mensagem,
            ]);
            event(new MensagemSosEvent( $mensagem ));
        }
        return redirect()->back()->with('success','menagem enviada com sucesso');
    }


    //Remove a notificação. Esta acção é invocada quando o user recebe e lê a notificacao ou a mensagem
    public function deletarMensagemLida(Request $request)
    {
        MensagemSos::destroy($request->id);

        return response()->json('Deletado com sucesso', 200);
    }

    public function pegarMensagensNaoLidas(){
        $MensagemSos = MensagemSos::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get(['id', 'conteudo', 'created_at as data']);
        return $MensagemSos;
    }

    public function responder($id)
    {
        $mensagem = MensagemSos::findOrFail($id);
        return view('chat_2', compact('mensagem'));
    }




}
