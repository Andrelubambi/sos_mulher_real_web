<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ==================  Métodos Gerais  ============

    public function edit($id)
{
    $user = User::findOrFail($id);
    return response()->json($user);
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'name' => 'required|string|max:255',
       'telefone' => "required|regex:/^(\+?[1-9]{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{5,15}$/|unique:users,telefone,$id",
    ]);

    $user->update($validated);

    return redirect()->back()->with('success', 'Dados atualizados com sucesso!');
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return redirect()->back()->with('success', 'Usuário deletado com sucesso!');
}


// ==================  Doutores  ============

public function createDoutor()
{
    $users = User::where('role', 'doutor')->get();
    return view('doutor', compact('users'));
}

public function storeDoutor(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'telefone' => 'required|unique:users,telefone|regex:/^(\+?[1-9]{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{5,15}$/',
        'password' => 'required|string|min:6',
    ]);

    try {
        $user = User::create([
            'name' => $validated['name'],
            'telefone' => $validated['telefone'],
            'password' => Hash::make($validated['password']),
            'role' => 'doutor',
        ]);

        return redirect()->route('users.doutor')->with('success', 'Doutor adicionado com sucesso!');

    } catch (\Exception $e) {
        return redirect()->route('users.doutor')->with('error', 'Falha ao cadastrar doutor: ' . $e->getMessage());
    }
}



// ==================  Estagiário  ============

public function createEstagiario()
{
    $users = User::where('role', 'estagiario')->get();
    return view('estagiario', compact('users'));
}

public function storeEstagiario(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'telefone' => 'required|unique:users,telefone|regex:/^(\+?[1-9]{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{5,15}$/',
        'password' => 'required|string|min:6',
    ]);

    try {
        User::create([
            'name' => $validated['name'],
            'telefone' => $validated['telefone'],
            'password' => Hash::make($validated['password']),
            'role' => 'estagiario',
        ]);

        return redirect()->route('users.estagiario')->with('success', 'Estagiário adicionado com sucesso!');

    } catch (\Exception $e) {
        return redirect()->route('users.estagiario')->with('error', 'Falha ao cadastrar estagiário: ' . $e->getMessage());
    }
}
  


                                    // =================================  Vítima  ============================================= //

public function createVitima()
{
    $users = User::where('role', 'estagiario')->get();
    return view('vitima', compact('users'));
}

public function storeVitima(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'telefone' => 'required|unique:users,telefone|regex:/^(\+?[1-9]{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{5,15}$/',
        'password' => 'required|string|min:6',
    ]);

    try {
        User::create([
            'name' => $validated['name'],
            'telefone' => $validated['telefone'],
            'password' => Hash::make($validated['password']),
            'role' => 'vitima',
        ]);

        return redirect()->route('users.vitima')->with('success', 'Vitima adicionada com sucesso!');

    } catch (\Exception $e) {
        return redirect()->route('users.vitima')->with('error', 'Falha ao cadastrar estagiário: ' . $e->getMessage());
    }
}
  
}
