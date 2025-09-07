<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;

class DoutorDashboardController extends Controller
{
    public function index()
    {
        $doutorId = Auth::id();

        // Conta o número de pacientes únicos com base nas consultas do médico
        $pacientesCount = Consulta::where('medico_id', $doutorId)
            ->distinct('vitima_id')
            ->count('vitima_id');
        
        // Obtém as próximas consultas
        $proximasConsultas = Consulta::where('medico_id', $doutorId)
            ->where('data', '>=', now())
            ->orderBy('data', 'asc')
            ->get();
        
        // Passa as duas variáveis para a view
        return view('dashboards.doutor', compact('pacientesCount', 'proximasConsultas'));
    }
}
