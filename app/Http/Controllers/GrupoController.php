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
        return view('grupos.index', compact('grupos'));
    }

 public function create()
    {
        // Pega todos os usuários, exceto o usuário logado
        $usuariosDisponiveis = User::where('id', '!=', auth()->id())->get();
        
        $grupos = Grupo::all(); 

        // Retorna a view e passa os dados
        return view('grupos.create', compact('usuariosDisponiveis', 'grupos'));
    }
 
 


public function store(Request $request)
{
    // 1. Validação (Adicionando validação para 'membros' como array)
    $validatedData = $request->validate([
        'nome' => 'required|string|max:255',
        'descricao' => 'nullable|string',
        'membros' => 'nullable|array', // Adicionado: 'membros' é opcional e deve ser um array
        'membros.*' => 'exists:users,id', // Opcional: Garante que os IDs existam na tabela users
    ]);
    
    try {
        // 2. Criação do Grupo
        $grupo = Grupo::create([
            'nome' => $validatedData['nome'],
            'descricao' => $validatedData['descricao'] ?? null, // Use nullish coalescing para nullable
            'admin_id' => auth()->id(),
        ]);

        // 3. Lógica para adicionar membros
        $membrosIds = [];
        
        if ($request->has('membros')) {
            // Se houver membros no request, inclua-os
            $membrosIds = $request->membros;
        }

        // Garante que o administrador (usuário logado) esteja sempre incluído e remove duplicatas
        $membrosParaSincronizar = array_unique(array_merge($membrosIds, [auth()->id()]));

        // Sincroniza os membros na tabela pivot
        $grupo->users()->sync($membrosParaSincronizar); 
        
        // 4. Retorno JSON de sucesso
        return response()->json([
            'success' => true,
            'message' => 'Grupo **' . $grupo->nome . '** criado com sucesso! 🎉',
            'grupo_id' => $grupo->id
        ], 201);

    } catch (\Exception $e) { 
        // 5. Retorno JSON de erro
        return response()->json([
            'success' => false,
            'message' => 'Erro interno ao salvar o grupo. Por favor, tente novamente.',
            'error' => $e->getMessage()
        ], 500);
    }
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

        // --- CORREÇÃO: Define e passa $usuariosDisponiveis para a view ---
        // Pega todos os usuários, exceto o usuário logado
        $usuariosDisponiveis = User::where('id', '!=', auth()->id())->get();

        // Retorna a view e passa os dados, incluindo agora $usuariosDisponiveis
        return view('grupos.show', compact('grupo','grupos', 'mensagens', 'usuarios', 'usuariosDisponiveis'));
    }

    

    public function destroy(Grupo $grupo)
    {
    if (auth()->id() !== $grupo->admin_id) {
        return redirect()->route('grupos.index')->with('error', 'Apenas o administrador do grupo pode excluir.');
    }

    $grupo->delete();

    return redirect()->route('grupos.index')->with('success', 'Grupo excluído com sucesso!');
    }



    public function sendMessage(Request $request, Grupo $grupo)
    {

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

    public function adicionarMembros(Request $request, Grupo $grupo)
    {
        // 1. Apenas o admin pode adicionar
        if (auth()->id() !== $grupo->admin_id) {
            return response()->json(['success' => false, 'message' => 'Apenas o administrador do grupo pode adicionar membros.'], 403);
        }

        // 2. Validação
        $validatedData = $request->validate([
            'membros' => 'required|array',
            'membros.*' => 'exists:users,id',
        ]);
        
        $membrosParaAdicionar = collect($validatedData['membros'])->map(fn($id) => (int)$id)->toArray();

        // 3. Obtém os IDs dos membros atuais
        $membrosAtuais = $grupo->users->pluck('id')->toArray();
        
        // 4. Junta os novos membros com os atuais (garantindo que o admin continue lá)
        $novosMembros = array_unique(array_merge($membrosAtuais, $membrosParaAdicionar));

        // 5. Sincroniza (Anexa) os novos membros sem remover os existentes
        $grupo->users()->sync($novosMembros);

        return response()->json([
            'success' => true,
            'message' => count($membrosParaAdicionar) . ' membro(s) adicionado(s) com sucesso.',
        ], 200);
    }
} 