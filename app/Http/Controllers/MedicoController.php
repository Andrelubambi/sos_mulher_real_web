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
    return view('medicos.index', compact('medicos'));
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
        'email' => 'required|string|email|max:255|unique:users,email',
        'password' => 'required|string|confirmed|min:6',
        'role' => 'required|string'
    ]);

    $medico = new User();
    $medico->name = $validated['name'];
    $medico->telefone = $validated['telefone'];
    $medico->email = $validated['email'];
    $medico->password = Hash::make($validated['password']);
    $medico->role = $validated['role'];
    $medico->save();

    return redirect()->route('medico.index')->with('success', 'Médico adicionado com sucesso!');
}

public function listar()
{
    $medicos = User::where('role', 'doutor')->latest()->get();
    return view('partials.tabela-medico', compact('medicos'))->render();
}


    
}
