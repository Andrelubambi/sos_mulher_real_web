<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;

class EstagiarioDashboardController extends Controller
{
    public function index()
    {
        $grupos = Grupo::all();
        // Lógica para mostrar pacientes ou tarefas
        return view('dashboards.estagiario', compact('grupos'));
    }
}