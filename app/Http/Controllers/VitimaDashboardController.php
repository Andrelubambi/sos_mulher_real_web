<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use Illuminate\Support\Facades\Auth;
use App\Models\Consulta;

class VitimaDashboardController extends Controller
{
    public function index()
    {
       

        $user = Auth::user();
 

        $minhasConsultas = Consulta::with('medico')
            ->where('vitima_id', $user->id)
            ->get(); 
        
        
        $grupos = $user->grupos;
        
         

        return view('dashboards.vitima', compact('minhasConsultas', 'grupos'));   
    }
}