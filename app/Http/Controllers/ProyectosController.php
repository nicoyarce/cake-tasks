<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Proyecto;
use App\Tarea;
use App\TareaHija;
use App\Area;
use App\Http\Requests\ProyectosRequest;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class ProyectosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(Auth::user()->hasRole('Administrador')){
            $proyectos = Proyecto::paginate(10);            
        }
        else{
            $proyectos = Auth::user()->proyectos()->paginate(10);            
        }                      
        return view('proyectos.index', compact('proyectos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('proyectos.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectosRequest $request)
    {  
        $proyecto = new Proyecto($request->all());
        $proyecto->nombre = $request->nombre;
        $proyecto->fecha_inicio = $request->fecha_inicio;
        $proyecto->fecha_termino_original = $request->fecha_termino;
        $proyecto->fecha_termino = $request->fecha_termino;      
        $proyecto->save();
        flash('Proyecto registrado')->success();
        return redirect('proyectos');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function show(Proyecto $proyecto)
    {
        $tareas = $proyecto->tareas()->get();
        return view('proyectos.show', compact('proyecto', 'tareas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function edit(Proyecto $proyecto)
    {
        return view('proyectos.edit', compact('proyecto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectosRequest $request, Proyecto $proyecto)
    { 
        $proyectonuevo = Proyecto::find($proyecto->id);
        $proyectonuevo->fill($request->all());
        $proyectonuevo->save();
        flash('Proyecto actualizado')->success();
        return redirect('proyectos');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Proyecto $proyecto)
    {           
        $proyecto = Proyecto::find($proyecto)->first();
        $proyecto->delete();
        flash('Proyecto eliminado')->success();        
        return redirect('proyectos');
    }

    public function vistaCargarXLS(){
        return view('proyectos.cargarxls');
    }

    public function cargarXLS(Request $request){        
        $validatedData = $request->validate([
            'archivo' => 'required|file|mimes:xlsx',
        ]);        
        Excel::load($request->archivo, function($reader) {
            $area = Area::where('nombrearea', 'Otra')->first();
            $hoja1 = $reader->first();
            $fila = $reader->first()->first();
            //dd($fila);
            $proyecto = Proyecto::create([
                'nombre' => $fila->nombre,
                'fecha_inicio' => Date::createFromFormat('d M Y H:i', $fila->comienzo, 'America/Santiago')->toDateTimeString(),
                'fecha_termino_original' =>  Date::createFromFormat('d M Y H:i', $fila->fin, 'America/Santiago')->toDateTimeString(),
                'fecha_termino' =>  Date::createFromFormat('d M Y H:i', $fila->fin, 'America/Santiago')->toDateTimeString()
            ]);
            $proyecto->save();            
            $ultimaTareaMadre = new Tarea;   
            $primerIndicadorEncontrado = false;                    
            foreach ($hoja1 as $key=>$fila) {                              
                if(!$key == 0){                                     
                    if(!is_null($fila->indicador)){
                        $primerIndicadorEncontrado = true;
                        $tarea = new Tarea;
                        $tarea->nombre = $fila->nombre;
                        $tarea->fecha_inicio = Date::createFromFormat('d M Y H:i', $fila->comienzo, 'America/Santiago')->toDateTimeString();
                        $tarea->fecha_termino_original =  Date::createFromFormat('d M Y H:i', $fila->fin, 'America/Santiago')->toDateTimeString();
                        $tarea->fecha_termino =  Date::createFromFormat('d M Y H:i', $fila->fin, 'America/Santiago')->toDateTimeString();
                        $tarea->proyecto()->associate($proyecto);
                        $tarea->area()->associate($area);
                        $tarea->save();
                        $ultimaTareaMadre = $tarea;
                    }
                    elseif($primerIndicadorEncontrado){
                        $tareaHija = new TareaHija;
                        $tareaHija->nombre = $fila->nombre;
                        $tareaHija->fecha_inicio = Date::createFromFormat('d M Y H:i', $fila->comienzo, 'America/Santiago')->toDateTimeString();
                        $tareaHija->fecha_termino =  Date::createFromFormat('d M Y H:i', $fila->fin, 'America/Santiago')->toDateTimeString();
                        $tareaHija->nivel = $fila->nivel_de_esquema;
                        $tareaHija->tareaMadre()->associate($ultimaTareaMadre);
                        $tareaHija->save();
                    }                   
                }
            }            
        });
        flash('Proyecto importado correctamente')->success();
        return redirect('proyectos');
    }

    public function vistaCargarHijas(){
        $proyectos = Proyecto::all();
        return view('proyectos.cargarhijas', compact('proyectos'));
    }

    public function cargarHijas(Request $request){
        $validatedData = $request->validate([
            'archivo' => 'required|file|mimes:xlsx',
        ]);        
        Excel::load($request->archivo, function($reader) use($request) {            
            $hoja1 = $reader->first();
            $fila = $reader->first()->first();
            //dd($fila);
            $proyecto = Proyecto::find($request->proyecto_id);
            $ultimaTareaMadre = new Tarea;   
            $primerIndicadorEncontrado = false;                    
            foreach ($hoja1 as $key=>$fila) {                              
                if(!$key == 0){
                    if(!is_null($fila->indicador)){
                        $primerIndicadorEncontrado = true;
                        $tareasProyecto = $proyecto->tareas()->get();
                        /*$ultimaTareaMadre = $tareasProyecto::where('nombre', 'LIKE', "%{$fila->nombre}%")->get();*/
                        $ultimaTareaMadre = $tareasProyecto->filter(function ($tarea) use ($fila){
                            return false !== stristr($tarea->nombre, $fila->nombre);
                        });                        
                    }
                    elseif($primerIndicadorEncontrado){
                        $tareaHija = new TareaHija;
                        $tareaHija->nombre = $fila->nombre;
                        $tareaHija->fecha_inicio = Date::createFromFormat('d M Y H:i', $fila->comienzo, 'America/Santiago')->toDateTimeString();
                        $tareaHija->fecha_termino =  Date::createFromFormat('d M Y H:i', $fila->fin, 'America/Santiago')->toDateTimeString();
                        $tareaHija->nivel = $fila->nivel_de_esquema;
                        $tareaHija->tareaMadre()->associate($ultimaTareaMadre->first());
                        $tareaHija->save();
                    }                   
                }
            }           
        });
        flash('Tareas importadas correctamente')->success();
        return redirect('proyectos');   
    }
}
