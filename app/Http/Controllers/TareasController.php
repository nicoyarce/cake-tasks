<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Proyecto;
use App\Area;
use Illuminate\Http\Request;
use App\Http\Requests\TareasRequest;
use App\Http\Requests\ProyectosRequest;

class TareasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index(){    	
        $tareas = Tarea::all();        
    	//return view('tareas.index', compact('tareas'));
    }

    public function create($proyectoId){        
        $proyecto = Proyecto::find($proyectoId);
        $areas = Area::all();
        $avances = \DB::table('nomenclaturasAvance')->get();
        return view('tareas.create', compact('proyecto','areas','avances'));
    }

    public function store(TareasRequest $request){        
        $tarea = new Tarea;
        $area = Area::find($request->area_id);
        $proyecto = Proyecto::find($request->proyecto_id);
        $tarea->nombre = $request->nombre;
        $tarea->fecha_inicio = $request->fecha_inicio;
        $tarea->fecha_termino_original = $request->fecha_termino;
        $tarea->fecha_termino = $request->fecha_termino;
        $tarea->avance = $request->avance;
        $tarea->proyecto()->associate($proyecto);
        $tarea->area()->associate($area);
        $tarea->save();    
        flash('Tarea registrada')->success();        
        return redirect()->route('proyectos.show',$request->proyecto_id);
    }

    public function show(Tarea $tarea){        
        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea){
        $listaProyectos = Proyecto::all();
        $areas = Area::all();
        $avances = \DB::table('nomenclaturasAvance')->get();
        return view('tareas.edit', compact('tarea','listaProyectos','areas','avances'));
    }

    public function update(TareasRequest $request, Tarea $tarea){
        $tareanueva = Tarea::find($tarea->id);
        if($request->has('fecha_termino')){
            $tareanueva->fill($request->all());
        }else{
            $input = $request->except(['fecha_termino']);
            $tareanueva->fill($input);
        }       
        $tareanueva->save();
        flash('Tarea actualizada')->success();
        return redirect()->route('proyectos.show',$tareanueva->proyecto_id);
    }

    public function destroy(Tarea $tarea){        
        $temp = Tarea::where('id', $tarea->id)->first();        
        $tarea = Tarea::find($tarea)->first()->delete();
        flash('Tarea eliminada')->success(); 
        return redirect()->route('proyectos.show', $temp->proyecto_id);
    }
       
}
