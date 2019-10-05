<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Proyecto;
use App\Area;
use App\TareaHija;
use App\Observacion;
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
        if($request->has('critica')){
            $tarea->critica = true;
        }
        else{
            $tarea->critica = false;
        }               
        $tarea->avance = $request->avance;
        $tarea->proyecto()->associate($proyecto);
        $tarea->area()->associate($area);
        $tarea->save();
        foreach ($request->observaciones as $textoObservacion) {            
            $observacion = new Observacion();
            $observacion->contenido = $textoObservacion;
            $observacion->tarea()->associate($tarea);
            $observacion->save();            
        }
        flash('Tarea registrada')->success();        
        return redirect()->route('proyectos.show',$request->proyecto_id)->with('idTareaMod', $tarea->id);
    }

    public function edit(Tarea $tarea){
        $listaProyectos = Proyecto::all();
        $areas = Area::all();
        $avances = \DB::table('nomenclaturasAvance')->get();
        return view('tareas.edit', compact('tarea','listaProyectos','areas','avances'));
    }

    public function update(Request $request, Tarea $tarea){
        //dd($request);
        $validatedData = $request->validate([
            'fecha_termino' => 'nullable|after:fecha_termino_original',
        ]);
        $tareaNueva = Tarea::find($tarea->id);        
        if(Auth::user()->hasRole('Usuario')){
            $tareaNueva->fill($request->only('avance'));
        }
        else if(is_null($request->fecha_termino)){
            $tareaNueva->fill($request->except('fecha_termino'));
            $tareaNueva->fecha_termino = $tarea->fecha_termino;            
        }
        else{            
            $tareaNueva->fill($request->all());              
        } 
        if($request->has('critica')){
            $tareaNueva->critica = true;
        }
        else{
            $tareaNueva->critica = false;
        }        
        if($request->has('observaciones')){
            $tareaNueva->observaciones()->forceDelete();
            foreach ($request->observaciones as $textoObservacion) {
                if(!is_null($textoObservacion)){
                    $observacion = new Observacion();
                    $observacion->contenido = $textoObservacion;
                    $observacion->tarea()->associate($tareaNueva);
                    $observacion->save();
                }            
            }
        }        
        $tareaNueva->save();
        flash('Tarea actualizada')->success();
        return redirect()->route('proyectos.show',$tareaNueva->proyecto_id)->with('idTareaMod', $tareaNueva->id);
    }

    public function destroy(Tarea $tarea){        
        $proyectoId = $tarea->proyecto()->get()->first()->id;        
        Tarea::find($tarea->id)->forceDelete();        
        flash('Tarea eliminada')->success(); 
        return redirect()->route('proyectos.show', $proyectoId);
    }

    public function cargarVisor(Request $request){ 
        $response = [
          'tasks'  => []
        ];
        $tareaMadre = Tarea::find($request->tareaid);        
        $tareas = $tareaMadre->tareasHijas()->get();
        //dd($tareaMadre->fecha_inicio);
        $response['tasks'][] = [
            'id'            => $tareaMadre->id,
            'name'            => $tareaMadre->nombre,
            'progress'            => $tareaMadre->avance,
            'level'            => 0,
            'status' => "STATUS_ACTIVE",
            //'depends'            => $tareaMadre->id,
            'start'            => $tareaMadre->fecha_inicio->timestamp * 1000,
            //'duration'            => (string) "-"+$tareaMadre->id,
            'end'            => $tareaMadre->fecha_termino->timestamp * 1000,
            'collapsed'            => false,
            'hasChild' => true,
        ];
        foreach($tareas as $tarea){                    
            $response['tasks'][] = [
                'id'            => $tarea->id,
                'name'            => $tarea->nombre,
                'progress'            => $tarea->avance,
                'level'            => $tarea->nivel,
                'status' => "STATUS_ACTIVE",
                //'depends'            => $tarea->id,                
                'start'            => $tarea->fecha_inicio->timestamp * 1000,
                //'duration'            => (string) "-"+$tareaMadre->id,
                'end'            => $tarea->fecha_termino->timestamp * 1000,
                'collapsed'            => false,
            ];                      
        }    
    
        $response = json_encode($response);
        //dd($response);
        return view('visor', compact('response'));                
    } 
      
}
