<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Mostrar o perfil do usuário logado
    public function show()
    {
        $user = Auth::user(); // pega o usuário logado
        return view('profile', compact('user'));
    }

    // Atualizar dados do perfil
    public function update(Request $request)
    {
        $user = Auth::user();

        // Validação dos campos
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'telefone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'photo' => 'nullable|image|max:2048',
        ]);

        // Atualizar dados
        $user->name = $request->name;
        $user->email = $request->email;
        $user->telefone = $request->telefone;

        // Atualizar senha se preenchida
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // Atualizar foto de perfil se enviada
        if ($request->hasFile('photo')) {
            // Apagar foto antiga
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            // Salvar nova foto
            $user->profile_photo = $request->file('photo')->store('users', 'public');
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Perfil atualizado com sucesso!');
    }
}
