<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;

class EstagiarioDashboardController extends Controller
{
    public function index()
    {
        $grupos = Grupo::all(); 
        return view('dashboards.estagiario', compact('grupos'));
    }
}