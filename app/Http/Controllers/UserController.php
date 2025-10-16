<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Medico; 
use App\Models\Grupo; 
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


    public function listNaoDoutores()
    {
        $usuariosNaoDoutores = User::where('role', '!=', 'doutor')->get();
    
        dd($usuariosNaoDoutores);
        return view('usuarios.nao_doutores', compact('usuariosNaoDoutores'));
    }



    public function createDoutor()
    { 
        $grupos = Grupo::all();
        $users = User::where('role', 'doutor')->get();
        return view('doutor', compact('users','grupos'));
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



    // ==========================  Estagiário  =============================== //

    public function createEstagiario()
    { 
        $grupos = Grupo::all();
        $users = User::where('role', 'estagiario')->get();
        return view('estagiario', compact('users','grupos'));
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
        $grupos = Grupo::all();
        $users = User::where('role', 'vitima')->get();
        return view('vitima', compact('users','grupos'));   
    }


    public function storeVitima(Request $request)
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
                'role' => 'vitima',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vitima criada com sucesso!',
                'user' => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Falha ao criar vitima: ' . $e->getMessage()
            ], 500);
        }
    }


    // ==================  Minhas Consultas  ============

    public function minhasConsultas()
    {
        $user = auth()->user();  
        $consultas = $user->consultas;

        return view('consultas.minhas', compact('consultas'));
    }


    // ==================  Filtrar consultas por medico  ============

    /**
     * Atualiza a senha do usuário (autenticado ou alvo específico)
     */
    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'current_password' => 'nullable|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if (!empty($validated['current_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return back()->with('error', 'Senha atual incorreta.');
            }
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', 'Senha alterada com sucesso!');
    }
}