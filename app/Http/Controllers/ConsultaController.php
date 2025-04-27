<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta;
use App\Models\User; 
class ConsultaController extends Controller
{
    public function index()
    {
 
        $user = auth()->user();
    
     
        if (!$user) {
          
            return redirect()->route('login');
        }
    
        // Busca as consultas com base no papel (role) do usuário
        switch ($user->role) {
            case 'admin':
                // Administradores podem ver todas as consultas
                $consultas = Consulta::all();
                break;
            
            case 'medico':
                // Médicos veem apenas as consultas atribuídas a eles
                $consultas = Consulta::where('medico_id', $user->id)->get();
                break;
            
            case 'criador':
                // Criadores veem apenas as consultas criadas por eles
                $consultas = Consulta::where('criada_por', $user->id)->get();
                break;
    
            default:
                $consultas = [];
                break;
        }

       $medicos = User::where('role', 'doutor')->get();
    
        return view('consulta', compact('consultas', 'medicos'));
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
            Consulta::create([
                'descricao' => $validated['descricao'],
                'bairro' => $validated['bairro'],
                'provincia' => $validated['provincia'],
                'data' => $validated['data'],
                'criada_por' => auth()->user()->id,  
                'medico_id' => $validated['medico_id'], 
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

    
}
