<?php

namespace App\Http\Controllers;

use App\Proyecto;
use Illuminate\Http\Request;
use App\Http\Requests\ProyectosRequest;
use Carbon;

class ProyectosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $proyectos = Proyecto::paginate(15);
        foreach ($proyectos as $proyecto) {
            $final = Carbon::parse($proyecto->fecha_termino);
            $atrasoCalculado = $final->diffInDays($proyecto->fecha_termino_original);
            $proyecto->atraso = $atrasoCalculado;
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
        $tareas = $proyecto->tareas;
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
        $proyectonueva = Proyecto::findOrFail($proyecto->id);
        $proyectonueva->fill($request->all());
        $proyectonueva->save();
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
}
