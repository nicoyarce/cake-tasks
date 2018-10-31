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
        $proyecto = Proyecto::find($proyecto)->first()->delete();
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
            foreach ($hoja1 as $key=>$fila) {
                if(!$key == 0){
                    $tarea = new Tarea;
                    $tarea->nombre = $fila->nombre;
                    $tarea->fecha_inicio = Date::createFromFormat('d M Y H:i', $fila->comienzo, 'America/Santiago')->toDateTimeString();
                    $tarea->fecha_termino_original =  Date::createFromFormat('d M Y H:i', $fila->fin, 'America/Santiago')->toDateTimeString();
                    $tarea->fecha_termino =  Date::createFromFormat('d M Y H:i', $fila->fin, 'America/Santiago')->toDateTimeString();                
                    $tarea->proyecto()->associate($proyecto);
                    $tarea->area()->associate($area);
                    $tarea->save();
                }
            }            
        });
        flash('Proyecto importado correctamente')->success();
        return redirect('proyectos');
    }

    public function cargarVisor(Request $request){        
        $tarea = Tarea::find($request->all());
        //dd($tarea);
        $archivo = storage_path('app/FF. COCHRANE. PID-MOD. DIAGRAMA LÓGICO MOD. avance (03-08-2018).xlsx');
        // $tareasHijas = new TareaHija;         
        // Excel::load($archivo, function($reader) {           
        //     $hoja1 = $reader->first();
        //     foreach ($hoja1 as $key=>$fila) {
        //         if(!$key == 0){                    
        //             //if(similar_text($tareasHijas->nombre, $fila->nombre, 80)>10){
        //                 $tareasHijas->nombre=$fila->nombre;
        //             //}                    
        //         }
        //     }            
        // });
        // $tareasHijas->toJson();
        return view('visor');        
    }
}
