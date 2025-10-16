<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;
use App\Models\User;

class EstagiarioDashboardController extends Controller
{
    public function index()
    {
        $grupos = Grupo::all(); 
        $vitimas = User::where('role', 'vitima')->get();
        
        return view('dashboards.estagiario', compact('grupos', 'vitimas'));
    }
}