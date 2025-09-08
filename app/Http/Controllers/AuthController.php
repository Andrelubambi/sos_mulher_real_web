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
        // 1. Validação dos dados
        $credentials = $request->validate([
            'telefone' => 'required|string',
            'password' => 'required|string',
        ]);
 
    
        // 2. Tenta autenticar o usuário
       if (Auth::attempt(['telefone' => $credentials['telefone'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            $user = Auth::user();

            // 3. Redirecionamento com base na role
           switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.dashboard'); // Corrected route name
                case 'doutor':
                    return redirect()->route('doutor.dashboard'); // Corrected route name
                case 'estagiario':
                    return redirect()->route('estagiario.dashboard'); // Corrected route name
                case 'vitima':
                    // Corrected route name to match the definition in web.php
                    return redirect()->route('vitima.dashboard');
                default:
                    // Redireciona para uma página padrão se a role não for encontrada
                    return redirect()->route('home');
            } 
        }

        // 4. Retorna com erro se a autenticação falhar
        return back()->with('error', 'Telefone ou senha incorretos.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}