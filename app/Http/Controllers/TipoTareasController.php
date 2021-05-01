<?php

namespace App\Http\Controllers;

use App\TipoTarea;
use Illuminate\Http\Request;

class TipoTareasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tipo_tareas = TipoTarea::paginate(10);
        return view('tipo_tareas.index')->with('tipo_tareas', $tipo_tareas);
    }

    public function indexConModal()
    {   
        $abrir_modal = 1;
        $areas = TipoTarea::paginate(10);
        return view('tipo_tareas.index', compact('tipo_tareas', 'abrir_modal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo_tarea = new TipoTarea;
        $tipo_tarea->descripcion = $request->descripcion;
        $tipo_tarea->save();
        flash('Tipo de tarea registrado')->success();
        return redirect('tipotareas');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $tipo_tareas = TipoTarea::paginate(10);
        $editar = TipoTarea::find($id);
        return view('tipo_tareas.index', compact('tipo_tareas', 'editar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {                   
        $tipo_tareaNueva = TipoTarea::find($id);
        $tipo_tareaNueva->descripcion = $request->descripcion;
        $tipo_tareaNueva->save();             
        flash('Tipo tarea actualizado')->success();
        return redirect('tipotareas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $temp = TipoTarea::where('id', $id)->first()->delete();        
        flash('Tipo tarea eliminada')->success(); 
        return redirect('tipotareas');
    }
}
