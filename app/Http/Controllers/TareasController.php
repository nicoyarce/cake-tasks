<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tarea;

use App\Http\Requests\TareasRequest;

class TareasController extends Controller
{
    public function index(){
    	$tareas = Tarea::all();
        //Tarea::orderBy('id', 'ASC')->paginate(15);
    	return view('tareas.index', compact('tareas'));
    }

    public function store(TareasRequest $request){
        //dd(request()->all());
        Tarea::create($request->all());
        //or
        //Tarea::create(request(['nombre','avance']));
        return redirect('tareas');
    }

    public function create(){
       return view('tareas.create');
    }

    public function show(Tarea $tarea){     
        return view('tareas.show', compact('tarea'));
    }

    public function edit(Tarea $tarea){             
        return view('tareas.edit', compact('tarea'));
    }

    public function update(TareasRequest $request, Tarea $tarea){
        $tareanueva = Tarea::findOrFail($tarea->id);
        $tareanueva->fill($request->all());
        $tareanueva->save();
        return redirect('tareas');
    }

    public function destroy(Tarea $tarea){
        $tarea = Tarea::find($tarea)->first()->delete();        
        return redirect('tareas');
    }
       
}
