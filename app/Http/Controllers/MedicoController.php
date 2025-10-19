<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;

class MedicoController extends Controller
{

    public function index()
{
    $medicos = User::where('role', 'doutor')->get();
    return view('medico', compact('medicos'));
}



    public function create()
    {
        return view('medico.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|string'
        ]);
    
        $medico = new User();
        $medico->name = $validated['name'];
        $medico->telefone = $validated['telefone'];
        $medico->password = bcrypt($validated['password']);
        $medico->role = $validated['role'];
        $medico->save();
    
        return response()->json(['success' => true]);
    } 

    public function listar()
{
    $medicos = User::where('role', 'doutor')->latest()->get();

    return view('partials.tabela-medico', compact('medicos'))->render();
}

    
}
