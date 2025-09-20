<?php

namespace App\Http\Controllers;
use App\Models\SosAlerta;
use Illuminate\Http\Request;
use App\Events\SosAlertaCriado;

class SosAlertaController extends Controller
{
    public function enviarAlerta()
    {
        $alerta = SosAlerta::create([
            'user_id' => auth()->id()
        ]);

        broadcast(new SosAlertaCriado($alerta))->toOthers();

        return response()->json($alerta, 201);
    }

    public function atenderAlerta(SosAlerta $alerta)
    {
        if ($alerta->atendida) {
            return response()->json(['mensagem' => 'Este alerta já foi atendido.']);
        }

        $alerta->update(['atendida' => true]);

        return response()->json(['mensagem' => 'Alerta atendido com sucesso']);
    }

    public function listar()
    {
        return SosAlerta::with('usuario')->orderByDesc('created_at')->get();
    }
}
