<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consulta; 
class ConsultaController extends Controller
{
    public function index()
    {
        $user = auth()->user();
    
        if ($user->role === 'doutor') {
            return Consulta::where('medico_id', $user->id)->get();
        } else {
            return Consulta::where('criada_por', $user->id)->get();
        }
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'medico_id' => 'required|exists:users,id',
            'descricao' => 'required',
            'bairro' => 'required',
            'provincia' => 'required',
            'data' => 'required|date',
        ]);
    
        return Consulta::create([
            'criada_por' => auth()->id(),
            'medico_id' => $request->medico_id,
            'descricao' => $request->descricao,
            'bairro' => $request->bairro,
            'provincia' => $request->provincia,
            'data' => $request->data,
        ]);
    }
    
}
