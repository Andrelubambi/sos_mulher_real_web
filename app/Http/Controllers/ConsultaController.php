<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\User;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;

class ConsultaController extends Controller
{
    public function index()
    {
        $grupos = Grupo::all();
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $consultas = collect(); // Inicializa uma coleção vazia por padrão

        switch ($user->role) {
            case 'admin':
                // Administradores podem ver todas as consultas com as relações carregadas, ordenadas pela data de criação
                $consultas = Consulta::with(['medico', 'criador'])
                                     ->orderBy('created_at', 'desc')
                                     ->get();
                break;

            case 'medico':
                // Médicos veem apenas suas consultas
                $consultas = Consulta::with(['medico', 'criador'])
                                     ->where('medico_id', $user->id)
                                     ->get();
                break;

            case 'vitima':
            case 'criador':
                // Vítimas e criadores veem as consultas que eles criaram
                $consultas = Consulta::with(['medico', 'criador'])
                                     ->where('criada_por', $user->id)
                                     ->get();
                break;
            
            default:
                // Caso o papel do usuário não seja reconhecido
                return redirect()->route('home')->with('error', 'Seu papel de usuário não tem permissão para visualizar esta página.');
        }

        $medicos = User::where('role', 'doutor')->get();
        return view('consulta', compact('consultas', 'medicos', 'grupos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'descricao' => 'required|string|max:255',
            'bairro' => 'required|string|max:255',
            'provincia' => 'required|string|max:255',
            'data' => 'required|date',
            'medico_id' => 'required|exists:users,id',
        ]);
    
        try {
            // Verifica se o usuário está autenticado antes de obter o ID
            if (!auth()->check()) {
                return redirect()->back()->with('error', 'Você deve estar logado para criar uma consulta.');
            }

            $userId = auth()->user()->id;

            Consulta::create([
                'descricao' => $validated['descricao'],
                'bairro' => $validated['bairro'],
                'provincia' => $validated['provincia'],
                'data' => $validated['data'],
                'criada_por' => $userId,
                'vitima_id' => $userId, // Adicionando o ID do usuário logado
                'medico_id' => $validated['medico_id'],
                'status' => 'pendente', // Adicionando o status com valor padrão
            ]);
    
            return redirect()->route('consultas.index')->with('success', 'Consulta criada com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Falha ao criar consulta: ' . $e->getMessage());
        }
    }

    public function criarConsulta()
    {
        $medicos = User::where('role', 'doutor')->get();
        return view('consultas', compact('medicos'));
    }

    public function edit($id)
    {
        $consulta = Consulta::findOrFail($id);
        return response()->json($consulta);
    }

    public function update(Request $request, $id)
    {
        $consulta = Consulta::findOrFail($id);

        $validated = $request->validate([
            'medico_id' => 'required|exists:users,id',
            'descricao' => 'required|string',
            'bairro' => 'required|string',
            'provincia' => 'required|string',
            'data' => 'required|date'
        ]);
        
        $consulta->update($validated);
        return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $consulta = Consulta::findOrFail($id);
        $consulta->delete();
        return redirect()->back()->with('success', 'Consulta deletada com sucesso!');
    }
}
