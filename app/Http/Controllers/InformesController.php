<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Proyecto;
use Barryvdh\DomPDF\Facade as PDF;

class InformesController extends Controller
{
    public function vistaInformes(){
        $proyectos = Proyecto::all();
        return view('informes', compact('proyectos'));
    }

    public function generarInforme(Request $request){
        $proyecto = Proyecto::find($request->proyecto_id);
        $tareas = $proyecto->tareas()->get();
        $pdf = PDF::loadView('proyectos.show', compact('proyecto', 'tareas'));        
        return $pdf->stream();
    }
}
