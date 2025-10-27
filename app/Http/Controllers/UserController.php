<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Medico; 
use App\Models\Grupo; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; 


class UserController extends Controller
{
    // ==================  Métodos Gerais  ============

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return response()->json([ 
            'success' => true,
            'user' => $user // Agora retorna o objeto User
        ], 200); // Status 200 OK
    }

     
// No UserController.php

 public function destroy($id, Request $request)
    {
        $user = User::findOrFail($id);
        $user->delete();

        $successMessage = 'Utilizador removido com sucesso!';
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ], 200); // Status 200 OK
        }
    
        return redirect()->route('users.doutor')->with('success', $successMessage); 
    }
    // ==================  Doutores  ============


    public function listNaoDoutores()
    {
        $usuariosNaoDoutores = User::where('role', '!=', 'doutor')->get();
     
        return view('usuarios.nao_doutores', compact('usuariosNaoDoutores'));
    }



    public function createDoutor()
    { 
        $grupos = Grupo::all();
        $users = User::where('role', 'doutor')->get();
        return view('medicos.index', compact('users','grupos'));
    }

  public function storeDoutor(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'telefone' => 'required|unique:users,telefone|regex:/^(\+?[1-9]{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{5,15}$/',
        'password' => 'required|string|min:6',
    ]);

    $successMessage = 'Doutor adicionado com sucesso!';
    $failMessage = 'Falha ao cadastrar doutor';

    try {
        User::create([  
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'password' => Hash::make($validated['password']),
            'role' => 'doutor',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
        }
        
        return redirect()->route('users.doutor')->with('success', $successMessage);

    } catch (\Exception $e) {
        $failMessage .= ': ' . $e->getMessage();
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $failMessage
            ], 500); // Erro de servidor ou BD
        }
        
        return redirect()->route('users.doutor')->with('error', $failMessage);
    }
}


    // ==========================  Estagiário  =============================== //

    public function createEstagiario()
    { 
        $grupos = Grupo::all();
        $users = User::where('role', 'estagiario')->get();
        return view('estagiarios.index', compact('users','grupos'));
    }

   public function storeEstagiario(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email', 
        'telefone' => 'required|unique:users,telefone|regex:/^(\+?[1-9]{1,4}[\s-]?)?(\(?\d{1,3}\)?[\s-]?)?[\d\s-]{5,15}$/',
        'password' => 'required|string|min:6',
    ]);

    $successMessage = 'Estagiário adicionado com sucesso!';
    $failMessage = 'Falha ao cadastrar estagiário';

    try {
        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'password' => Hash::make($validated['password']),
            'role' => 'estagiario',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
        }

        return redirect()->route('users.estagiario')->with('success', $successMessage);

    } catch (\Exception $e) {
        $failMessage .= ': ' . $e->getMessage();
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $failMessage
            ], 500);
        }

        return redirect()->route('users.estagiario')->with('error', $failMessage);
    }
}


    // =================================  Vítima  ============================================= //


    public function indexVitima()
   {
       Log::info('--- INÍCIO: Método indexVitima ---'); // Log de início

       $users = User::where('role', 'vitima')->get();
      
       $grupos = Grupo::all(); // 2. Carrega todos os grupos
   
       return view('vitimas.index', compact('users', 'grupos'));

   }
 

public function storeVitima(Request $request)
{
    // 1. Validação
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'telefone' => 'required|unique:users,telefone',
        'password' => 'required|string|min:6',
        'role' => 'required|in:vitima',
    ]);

    // 2. Verifica se a requisição é AJAX (vindo do seu jQuery)
    if ($request->ajax() || $request->wantsJson()) {
        try {
            User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'telefone' => $validated['telefone'],
                'password' => Hash::make($validated['password']),
                'role' => 'vitima',
            ]);

            // Sucesso - Retorna JSON
            return response()->json([
                'success' => true,
                'message' => 'Conta criada com sucesso! Redirecionando para o Login.'
            ], 200);

        } catch (\Exception $e) {
            // Erro de BD, etc. - Retorna JSON com status 500
            return response()->json([
                'success' => false,
                'message' => 'Falha ao criar conta: ' . $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }
    
    // Fallback: Se não for AJAX, faz o redirecionamento tradicional (menos comum para esta rota)
    try {
         User::create([
             'name' => $validated['name'],
             'email' => $validated['email'],
             'telefone' => $validated['telefone'], 
             'password' => Hash::make($validated['password']),
             'role' => 'vitima',
         ]);
        return redirect()->route('login.form')->with('success', 'Conta criada com sucesso! Faça login.');
    } catch (\Exception $e) {
        return redirect()->back()->withInput()->with('error', 'Falha ao criar conta: ' . $e->getMessage());
    }
}

public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    // Validação
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => "required|email|unique:users,email,{$id}",
        'telefone' => 'nullable|string|max:20',
    ]);

    $user->update($validated);

    $successMessage = 'Vítima atualizada com sucesso!';

    if ($request->wantsJson() || $request->ajax()) {
        return response()->json([
            'success' => true,
            'message' => $successMessage
        ]);
    }

    // Fallback
    // OBS: Rota 'vitimas.vitimas' parece incorreta
    return redirect()->route('users.vitima')->with('success', $successMessage);
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

    $successMessage = 'Senha alterada com sucesso!';
    
    if (!empty($validated['current_password']) && !Hash::check($validated['current_password'], $user->password)) {
        $errorMessage = 'Senha atual incorreta.';
        
        if ($request->wantsJson() || $request->ajax()) {
             return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 400); // Bad Request
        }
        return back()->with('error', $errorMessage);
    }

    $user->password = Hash::make($validated['password']);
    $user->save();

    if ($request->wantsJson() || $request->ajax()) {
         return response()->json([
            'success' => true,
            'message' => $successMessage
        ]);
    }
    return back()->with('success', $successMessage);
}

 public function showProfile()
{
    $user = auth()->user();
    return view('profile', compact('user'));
}

public function updateProfile(Request $request)
{
    $user = auth()->user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => "required|email|unique:users,email,{$user->id}",
        'telefone' => 'nullable|string|max:20',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $successMessage = 'Perfil atualizado com sucesso!';
    $failMessage = 'Falha ao atualizar perfil.';

    try {
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('profile_photos', 'public');
            $user->photo = $path;
        }

        $user->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage
            ]);
        }
        return back()->with('success', $successMessage);
    
    } catch (\Exception $e) {
        $failMessage .= ': ' . $e->getMessage();
        
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $failMessage
            ], 500);
        }
        return back()->with('error', $failMessage);
    }
}
}