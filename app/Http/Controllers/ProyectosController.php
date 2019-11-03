<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Proyecto;
use App\Tarea;
use App\TareaHija;
use App\Observacion;
use App\User;
use App\Http\Requests\ProyectosRequest;
use Jenssegers\Date\Date;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProyectosImport;
use App\Imports\TareasImport;
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
    
    public function indexArchivados()
    {
        if(Auth::user()->hasRole('Administrador')){
            $proyectos = Proyecto::onlyTrashed()->get()
                ->sortBy('deleted_at')->values()->all();            
        }
        else{
            $proyectos = Auth::user()->proyectos()->onlyTrashed()->get()
                ->sortBy('deleted_at')->values()->all();
        }                      
        return view('proyectos.indexarchivados', compact('proyectos'));
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
        foreach ($request->observaciones as $textoObservacion) {   
            if(!is_null($textoObservacion)){         
                $observacion = new Observacion();
                $observacion->contenido = $textoObservacion;
                $observacion->proyecto()->associate($proyecto);
                $observacion->user()->associate(User::find(Auth::user()->id))->save();
                $observacion->save();
            }        
        }
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
        $tareas = $proyecto->tareas
            ->sortBy(function($tarea) {
                return [$tarea->fecha_inicio, $tarea->fecha_termino];
            })->values()->all();        
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
        $proyectoNuevo = Proyecto::find($proyecto->id);       
        $proyectoNuevo->nombre = $request->nombre;
        $proyectoNuevo->fecha_termino = $request->fecha_termino;
        if($request->has('fecha_termino') && $request->fecha_termino != $proyecto->fecha_termino) {            
            $proyectoNuevo->autorUltimoCambioFtr()->associate(User::find(Auth::user()->id))->save();
            $proyectoNuevo->fecha_ultimo_cambio_ftr = Date::now();
        }
        if($request->has('observaciones')){
            $ids_observaciones = collect($request->ids_observaciones);            
            Observacion::whereNotIn('id', $ids_observaciones)->forceDelete();
            $observacionesRestantes = $proyectoNuevo->observaciones()->get()->pluck('contenido');
            foreach ($request->observaciones as $n => $textoObservacion) {                                
                if(!is_null($textoObservacion) && !$observacionesRestantes->contains($textoObservacion)){
                    $observacion = new Observacion();
                    $observacion->contenido = $textoObservacion;
                    $observacion->proyecto()->associate($proyectoNuevo);
                    $observacion->autor()->associate(User::find(Auth::user()->id));
                    $observacion->save();
                }                
            }
        }
        $proyectoNuevo->save();        
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
        $string = 'command:generaInforme';
        \Artisan::call($string, ['proyecto' => $proyecto->id]);
        $proyecto = Proyecto::destroy($proyecto->id);
        flash('Proyecto archivado')->success();        
        return redirect('proyectos');
    }

    public function showArchivados($id)
    {
        $proyecto = Proyecto::withTrashed()
            ->where('id', $id)
            ->get()
            ->first();
        //dd($proyecto);      
        $tareas = $proyecto->tareas()->withTrashed()->get()
            ->sortBy(function($tarea) {
                return [$tarea->fecha_inicio, $tarea->fecha_termino];
            })->values()->all();
        //dd(count($proyecto->tareas()->withTrashed()->get()));
        return view('proyectos.show', compact('proyecto', 'tareas'));
    }

    public function restaurar($id){        
        $proyecto = Proyecto::withTrashed()->find($id);
        $proyecto->restore();
        $proyecto->informes()->withTrashed()->restore(); 
        $proyecto->tareas()->withTrashed()->restore();
        //$proyecto->tareasHijas()->withTrashed()->restore(); //usar esto en laravel 5.8        
        foreach($proyecto->tareas()->withTrashed()->get() as $tarea){
            $tarea->restore();
            foreach($tarea->tareasHijas()->withTrashed()->get() as $tareaHija){
                $tareaHija->restore();
            }
        }
        flash('Proyecto restaurado')->success();        
        return redirect('proyectosArchivados');
    }

    public function eliminarPermanente($id){        
        $proyecto = Proyecto::withTrashed()->find($id);        
        $proyecto->informes()->withTrashed()->forceDelete();         
        //$proyecto->tareasHijas()->withTrashed()->forceDelete(); //usar esto en laravel 5.8        
        foreach($proyecto->tareas()->withTrashed()->get() as $tarea){            
            foreach($tarea->tareasHijas()->withTrashed()->get() as $tareaHija){
                $tareaHija->forceDelete();
            }
            $tarea->forceDelete();
        }
        $proyecto->forceDelete();
        flash('Proyecto eliminado')->success();        
        return redirect('proyectosArchivados');
    }

    public function vistaCargarXLS(){
        return view('proyectos.cargarxls');
    }

    public function cargarXLS(Request $request){        
        $validatedData = $request->validate([
            'archivo' => 'required|file|mimes:xlsx',
        ]);
        Excel::import(new ProyectosImport, $request->archivo);        
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
        Excel::import(new TareasImport, $request->archivo);
        flash('Tareas importadas correctamente')->success();
        return redirect('proyectos');   
    }

    
}
