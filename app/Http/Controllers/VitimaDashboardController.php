<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;

class VitimaDashboardController extends Controller
{
    public function index()
{
    $userId = Auth::id();
    $minhasConsultas = Consulta::with('medico') // Carregar relação medico
        ->where('vitima_id', $userId)
        ->get();

    return view('dashboards.vitima', compact('minhasConsultas'));
}
}