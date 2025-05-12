<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grupo;

class DashBoardController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with('admin')->get();
        return view('index', compact('grupos'));
    }
}
