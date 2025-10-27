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

        $consultas = collect();

        switch ($user->role) {
            case 'admin':
                $consultas = Consulta::with(['medico', 'criador'])
                                     ->orderBy('created_at', 'desc')
                                     ->get();
                break;

            case 'doutor': 
                $consultas = Consulta::with(['medico', 'criador'])
                                     ->where('medico_id', $user->id)
                                     ->get();
                break;

            case 'vitima':
            case 'criador':
                $consultas = Consulta::with(['medico', 'criador'])
                                     ->where('criada_por', $user->id)
                                     ->get();
                break;
            
            default:
                return redirect()->route('home')->with('error', 'Seu papel de usuário não tem permissão para visualizar esta página.');
        }

        $medicos = User::where('role', 'doutor')->get();
        return view('consultas.consulta', compact('consultas', 'medicos', 'grupos'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'descricao' => 'required|string|max:255',
        'bairro' => 'required|string|max:255',
        'provincia' => 'required|string|max:255',
        'data' => [
            'required',
            'date',
            function ($attribute, $value, $fail) {
                $data = \Carbon\Carbon::parse($value);
                $amanha = now()->addDay();
                $limite = now()->addDays(15);

                if ($data->lessThan($amanha) || $data->greaterThan($limite)) {
                    $fail('A data deve estar entre amanhã e os próximos 15 dias.');
                }
            },
        ],
        'medico_id' => 'required|exists:users,id',
    ]);

    try {
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
            'vitima_id' => $userId,
            'medico_id' => $validated['medico_id'],
            'status' => 'pendente',
        ]);

        return redirect()->route('consulta')->with('success', 'Consulta criada com sucesso!');
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
    $consulta = Consulta::find($id);
    if (!$consulta) {
        return response()->json(['message' => 'Consulta não encontrada.'], 404);
    }

    return response()->json(['consulta' => $consulta]);
}


    public function update(Request $request, $id)
    {
        $consulta = Consulta::findOrFail($id);

        $validated = $request->validate([ 
            'medico_id' => 'required|exists:users,id',
            'descricao' => 'required|string',
            'bairro' => 'required|string',
            'provincia' => 'required|string',
            'data' => [
            'required',
            'date',
                function ($attribute, $value, $fail) {
                    $data = \Carbon\Carbon::parse($value);
                    $amanha = now()->addDay();
                    $limite = now()->addDays(15);

                if ($data->lessThan($amanha) || $data->greaterThan($limite)) {
            $fail('A data deve estar entre amanhã e os próximos 15 dias.');
                }
            },
        ],
        ]);
        
        $consulta->update($validated);
        return redirect()->route('consulta')->with('success', 'Dados atualizados com sucesso!');
    }

    public function destroy($id)
    {
        $consulta = Consulta::findOrFail($id);
        $consulta->delete();
        return redirect()->route('consulta')->with('success', 'Consulta deletada com sucesso!');
    }
}