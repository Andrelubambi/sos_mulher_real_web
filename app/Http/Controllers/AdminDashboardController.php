<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vitima;
use App\Models\Consulta;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Adicione esta linha

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Grupos
        $grupos = Grupo::all();

        // Contagens
        $estagiariosCount = User::where('role', 'estagiario')->count();
        $doutoresCount = User::where('role', 'doutor')->count();
        $vitimasCount = Vitima::count();
        $consultasMarcadasCount = Consulta::count();

        // Detalhes
        $estagiarios = User::where('role', 'estagiario')->get();
        $doutores = User::where('role', 'doutor')->get();
        $vitimas = Vitima::all();
        $consultasMarcadas = Consulta::with(['medico', 'vitima','criador'])->get();
        // Agrupamentos ou outras estatísticas
        $consultasPorDoutor = Consulta::select('medico_id')
            ->with('medico') 
            ->groupBy('medico_id')
            ->selectRaw('medico_id, COUNT(*) as total')
            ->get();

        $consultasPorData = Consulta::selectRaw('DATE(created_at) as data, COUNT(*) as total')
            ->groupBy(DB::raw('DATE(created_at)')) // Aqui usamos DB::raw corretamente
            ->orderBy('data', 'desc')
            ->take(10)
            ->get();

        // NOVO: Coleta dados de consultas por status para o gráfico de donut
        $consultasPorStatus = Consulta::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact(
            'estagiariosCount', 'doutoresCount', 'vitimasCount', 'consultasMarcadasCount',
            'estagiarios', 'doutores', 'vitimas', 'consultasMarcadas', 'grupos',
            'consultasPorDoutor', 'consultasPorData', 'consultasPorStatus'
        ));
    }
}
