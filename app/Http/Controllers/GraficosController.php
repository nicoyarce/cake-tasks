<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;
use App\Area;

class GraficosController extends Controller
{
    public function index(){
        \JavaScript::put(['tarea' => Tarea::sacarDatos(0)]);
        $areas = Area::all();        
        return view('grafico', compact('areas'));
    }   

    public function filtrar(Request $request){
        \JavaScript::put(['tarea' => Tarea::sacarDatos($request->opcion)]);
        $areas = Area::all();        
        return view('grafico', compact('areas'));
    }
}
