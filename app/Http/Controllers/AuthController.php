<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function showLoginForm()
    {
        return view('login'); 
    }

    public function login(Request $request)
    {
       
        $request->validate([
            'telefone' => 'required|string',
            'password' => 'required|string',
        ]);

     
        $user = User::where('telefone', $request->telefone)->first();

        if (!$user ) {
            return redirect()->back()->with('error', 'Telefone ou senha incorretos.');
        }

    
        if (Auth::loginUsingId($user->id)) {
            $token = $user->createToken('token_app')->plainTextToken;
            return redirect()->route('index')->with([
                'success' => 'Login realizado com sucesso!',
                'user' => $user, 
                'token' => $token, 
            ]);
        }
        return redirect()->back()->with('error', 'Falha ao autenticar usuário.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('login');
    }
    
    
}
