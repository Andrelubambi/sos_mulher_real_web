<?php

namespace App\Http\Controllers;
use App\Models\GrupoApoio;
use Illuminate\Http\Request;

class GrupoApoioController extends Controller
{
    public function index()
    {
        return GrupoApoio::with('membros')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'membros' => 'required|array',
            'membros.*' => 'exists:users,id'
        ]);

        $grupo = GrupoApoio::create(['nome' => $request->nome]);
        $grupo->membros()->sync($request->membros);

        return response()->json($grupo->load('membros'), 201);
    }

    public function show($id)
    {
        return GrupoApoio::with('membros')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $grupo = GrupoApoio::findOrFail($id);
        $grupo->update($request->only('nome'));

        if ($request->has('membros')) {
            $grupo->membros()->sync($request->membros);
        }

        return $grupo->load('membros');
    }

    public function destroy($id)
    {
        $grupo = GrupoApoio::findOrFail($id);
        $grupo->membros()->detach();
        $grupo->delete();

        return response()->json(['mensagem' => 'Grupo removido com sucesso.']);
    }
}
