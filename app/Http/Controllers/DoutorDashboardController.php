<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;
use App\Models\User;
use Carbon\Carbon;

class DoutorDashboardController extends Controller
{
    public function index()
    {
        $doutorId = Auth::id();

        // Conta o número de pacientes únicos
        $pacientesCount = Consulta::where('medico_id', $doutorId)
            ->distinct('vitima_id')
            ->count('vitima_id');
        
        // Obtém as próximas consultas com a relação vitima
        $proximasConsultas = Consulta::where('medico_id', $doutorId)
            ->where('data', '>=', now())
            ->with('vitima')
            ->orderBy('data', 'asc')
            ->get();
        
        // Consultas realizadas (com data anterior à atual)
        $consultasRealizadas = Consulta::where('medico_id', $doutorId)
            ->where('data', '<', now())
            ->count();
        
        // Consultas de hoje
        $consultasHoje = Consulta::where('medico_id', $doutorId)
            ->whereDate('data', today())
            ->count();
        
        // Lista de pacientes (vítimas) do médico
        $pacientes = User::whereIn('id', function($query) use ($doutorId) {
                $query->select('vitima_id')
                    ->from('consultas')
                    ->where('medico_id', $doutorId)
                    ->distinct();
            })
            ->where('role', 'vitima')
            ->get();
        
        return view('dashboards.doutor', compact(
            'pacientesCount', 
            'proximasConsultas', 
            'consultasRealizadas',
            'consultasHoje',
            'pacientes'
        ));
    }
}