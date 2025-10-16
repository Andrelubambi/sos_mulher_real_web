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
                'enviado_por'=>auth()->id()
            ]);
            event(new MensagemSosEvent( $mensagem ));
        }
        return redirect()->back()->with('success','menagem enviada com sucesso');
    }


    public function mensagemLida(Request $request)
    {
        $mensagemSos = MensagemSos::find($request->id);
        $mensagemSos->status = 'lido';
        $mensagemSos->save();
        return response()->json('Deletado com sucesso', 200);
    }

    public function pegarMensagensNaoLidas(){
        $MensagemSos = MensagemSos::where('user_id', auth()->id())
                ->where('status','nao_lido')
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
