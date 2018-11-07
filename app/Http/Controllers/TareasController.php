<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Proyecto;
use App\Area;
use App\TareaHija;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTareasRequest;
use App\Http\Requests\UpdateTareasRequest;
use App\Http\Requests\ProyectosRequest;


class TareasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function show(Tarea $tarea){
        $tareasHijas = $tarea->tareasHijas()->get();
        return view('tareas.show', compact('tarea','tareasHijas'));
    }

    public function create($proyectoId){        
        $proyecto = Proyecto::find($proyectoId);
        $areas = Area::all();
        $avances = \DB::table('nomenclaturasAvance')->get();
        return view('tareas.create', compact('proyecto','areas','avances'));
    }

    public function store(StoreTareasRequest $request){        
        $tarea = new Tarea;
        $area = Area::find($request->area_id);
        $proyecto = Proyecto::find($request->proyecto_id);
        $tarea->nombre = $request->nombre;
        $tarea->fecha_inicio = $request->fecha_inicio;
        $tarea->fecha_termino_original = $request->fecha_termino;
        $tarea->fecha_termino = $request->fecha_termino;
        $tarea->observaciones = $request->observaciones;
        $tarea->avance = $request->avance;
        $tarea->proyecto()->associate($proyecto);
        $tarea->area()->associate($area);
        $tarea->save();    
        flash('Tarea registrada')->success();        
        return redirect()->route('proyectos.show',$request->proyecto_id);
    }

    public function edit(Tarea $tarea){
        $listaProyectos = Proyecto::all();
        $areas = Area::all();
        $avances = \DB::table('nomenclaturasAvance')->get();
        return view('tareas.edit', compact('tarea','listaProyectos','areas','avances'));
    }

    public function update(Request $request, Tarea $tarea){
        $validatedData = $request->validate([
            'fecha_termino' => 'nullable|after:fecha_termino_original',
        ]);
        $tareanueva = Tarea::find($tarea->id);        
        if(Auth::user()->hasRole('Usuario')){
            $tareanueva->fill($request->only('avance'));
        }
        else if(is_null($request->fecha_termino)){
            $tareanueva->fill($request->except('fecha_termino'));
            $tareanueva->fecha_termino = $tarea->fecha_termino;
        }
        else{            
            $tareanueva->fill($request->all());
        }       
        $tareanueva->save();        
        flash('Tarea actualizada')->success();
        return redirect()->route('proyectos.show',$tareanueva->proyecto_id)->with('idTareaMod', $tareanueva->id);
    }

    public function destroy(Tarea $tarea){        
        $temp = Tarea::where('id', $tarea->id)->first(); 
        $id = $temp->proyecto_id;      
        $tarea = Tarea::find($tarea)->first()->delete();
        $temp->first()->delete();
        flash('Tarea eliminada')->success(); 
        return redirect()->route('proyectos.show', $id);
    }

    public function cargarVisor(Request $request){ 
        $response = [
          'tasks'  => []
        ];
        $tareas = Tarea::find($request->tareaid)->tareasHijas()->get();        
        foreach($tareas as $tarea){
            $response['tasks'][] = [
                'id'            => $tarea->id,
                'name'            => $tarea->nombre,
                'progress'            => $tarea->avance,
                'level'            => $tarea->nivel,
                //'depends'            => $tarea->id,
                'start'            => strtotime($tarea->fecha_inicio) * 1000,
                //'duration'            => (string) "-"+$tarea->id,
                'end'            => strtotime($tarea->fecha_termino) * 1000,
                'collapsed'            => false,
            ];
        }    
    
        $response = json_encode($response);
        //dd($response);
        return view('visor', compact('response'));                
    } 
      
}
