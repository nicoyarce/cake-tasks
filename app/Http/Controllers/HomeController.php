<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use App\Tarea;

class HomeController extends Controller
{
    public function index(){        
        $nroProyectos = Proyecto::all()->count();        
        return view('welcome', compact('nroProyectos'));
    }
}
