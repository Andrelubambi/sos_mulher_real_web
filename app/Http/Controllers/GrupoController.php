<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\User;
use App\Models\MensagemGrupo;
use App\Events\GroupMessageSent;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with('admin')->get();
        return view('index', compact('grupos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $grupo = Grupo::create([
            'nome' => $request->nome,
            'descricao' => $request->descricao,
            'admin_id' => auth()->id(),
        ]);

        return redirect()->route('index')->with('success', 'Grupo criado com sucesso!');
    }


    public function getMensagens(Grupo $grupo)
{
    $mensagens = MensagemGrupo::where('grupo_id', $grupo->id)
        ->with('user') 
        ->orderBy('created_at', 'asc')
        ->get();

    return response()->json($mensagens);
}

    public function show(Grupo $grupo)
    {
        $grupos= Grupo::all();
        
        $mensagens = MensagemGrupo::where('grupo_id', $grupo->id)
        ->with('user')
        ->orderBy('created_at', 'asc')
        ->get();

        $usuarios = $grupo->users;

        return view('grupos.show', compact('grupo','grupos', 'mensagens', 'usuarios'));
    }

    



    public function destroy(Grupo $grupo)
    {
    if (auth()->id() !== $grupo->admin_id) {
        return redirect()->route('index')->with('error', 'Apenas o administrador do grupo pode excluir.');
    }

    $grupo->delete();

    return redirect()->route('index')->with('success', 'Grupo excluído com sucesso!');
    }



    public function sendMessage(Request $request, Grupo $grupo)
    {
        return response()->json($request, 200);

        $request->validate([
            'conteudo' => 'required|string|max:1000',
        ]);
    
        $mensagem = MensagemGrupo::create([
            'grupo_id' => $grupo->id,
            'user_id' => auth()->id(),
            'conteudo' => $request->conteudo,
        ]);
    
        event(new GroupMessageSent($mensagem));
    
        return response()->json($mensagem);
    }

    public function entrar(Grupo $grupo)
    {
        $grupo->users()->attach(auth()->id());
        return redirect()->route('grupos.show', $grupo->id)->with('success', 'Você entrou no grupo!');
    }


    
    public function sair(Grupo $grupo)
    {
        $grupo->users()->detach(auth()->id());
        return redirect()->route('grupos.index')->with('success', 'Você saiu do grupo!');
    }



    public function removerUsuario(Grupo $grupo, User $user)
    {
        if (auth()->id() !== $grupo->admin_id) {
            return redirect()->route('grupos.show', $grupo->id)->with('error', 'Apenas o administrador pode remover usuários.');
        }

        $grupo->users()->detach($user->id);
        return redirect()->route('grupos.show', $grupo->id)->with('success', 'Usuário removido do grupo.');
    }
}