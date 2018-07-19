<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tarea;
use App\Area;
use App\Proyecto;

class GraficosController extends Controller
{   
    public function show(Proyecto $proyecto){
        $areas = Area::all();  
        $tarea = Tarea::where('proyecto_id', $proyecto->id)->get();        
        $tarea = $tarea->toJson();
        return view('grafico', compact('proyecto','areas','tarea'));
    }

    public function filtrar(Request $request){        
        $proyectoid = $request->proyectoid;
        $areaid = $request->areaid;
        if($areaid == 0){
            $tarea = Tarea::where('proyecto_id', $proyectoid)->get();
        }
        else{
            $tarea = Tarea::where('proyecto_id', $proyectoid)
            ->where('area_id', $areaid)
            ->get();        
        }        
        return $tarea->toJson();        
     }
}
