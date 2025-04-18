<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|email|unique:users,email',
            'telefone' => 'required|string',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:estagiario,doutor',
        ]);

        if (auth()->user()->role !== 'admin') {
            return response()->json(['erro' => 'Apenas o administrador pode criar usuários especiais.'], 403);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'telefone' => $request->telefone,
            'password' => Hash::make($request->password),
            'role'     => $request->role
        ]);

        return response()->json($user, 201);
    }
}
