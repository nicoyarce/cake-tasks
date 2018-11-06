<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Proyecto;
use App\Area;
use App\TareaHija;
use App\Http\Resources\TareaHijaResource;
use App\Http\Resources\TareaHijaCollection;
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
    
    public function show(TareaHija $tarea){
        TareaHijaResource::withoutWrapping();
        return new TareaHijaResource($tarea);
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
        try{
            $statusCode = 200;
            $response = [
              'tasks'  => []
            ];

            $tareas = TareaHija::where('proyecto_id',$request->proyectoid)->where('id',$request->tareaid)->get();   

            foreach($tareas as $tarea){
                $response['tasks'][] = [
                    'id'            => $tarea->id,
                    'name'            => $tarea->nombre,
                    'progress'            => $tarea->avance,
                    'level'            => $tarea->nivel,
                    //'depends'            => $tarea->id,
                    'start'            => $tarea->fecha_inicio,
                    //'duration'            => (string) "-"+$tarea->id,
                    'end'            => $tarea->fecha_termino,
                    'collapsed'            => false,
                ];
            }

        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            $response = json_encode($response);
            //dd($response);
            return view('visor', compact('response'));
        }        
    } 
      
}
