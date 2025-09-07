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
        $proximasConsultas = Consulta::where('doutor_id', $doutorId)
            ->orderBy('data', 'asc')
            ->get();

        return view('dashboards.doutor', compact('proximasConsultas'));
    }
}