<?php

namespace App\Http\Controllers;

use App\Tarea;
use App\Proyecto;
use App\Area;
use Illuminate\Http\Request;
use App\Http\Requests\TareasRequest;

class TareasController extends Controller
{
    public function index(){
    	$tareas = Tarea::all();
        $tareas = Tarea::paginate(15);
    	return view('tareas.index', compact('tareas'));
    }

    public function create(){
        $listaProyectos = Proyecto::all();
        $areas = Area::all();
        $avances = \DB::table('nomenclaturasAvance')->get();
        return view('tareas.create', compact('listaProyectos','areas','avances'));
    }

    public function store(TareasRequest $request){
        //dd(request()->all());
        Tarea::create($request->all());        
        //Tarea::create(request(['nombre',,'fecha_inicio','fecha_termino','avance']));
        flash('Tarea registrada')->success();
        return redirect('tareas');
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
        $tareanueva = Tarea::findOrFail($tarea->id);
        $tareanueva->fill($request->all());
        $tareanueva->save();
        flash('Tarea actualizada')->success();
        return redirect('tareas');
    }

    public function destroy(Tarea $tarea){
        $tarea = Tarea::find($tarea)->first()->delete();       
        flash('Tarea eliminada')->success(); 
        return redirect('tareas');
    }
       
}
