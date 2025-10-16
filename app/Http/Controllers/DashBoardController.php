<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;

class DashBoardController extends Controller
{
    public function index()
    {
        $grupos = Grupo::all();  
        return view('index', compact('grupos'));
    }

    public function getAll()
    {
        $grupos = Grupo::all();  
        return view('index', compact('grupos'));
    }
}
