<?php

namespace App\Http\Controllers;

use App\Area;
use Illuminate\Http\Request;

class AreasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $areas = Area::paginate(10);
        return view('areas.index')->with('areas', $areas);
    }

    public function indexConModal()
    {
        $abrir_modal = 1;
        $areas = Area::paginate(10);
        return view('areas.index', compact('areas', 'abrir_modal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $area = new Area;
        $area->nombrearea = $request->nombre;
        $area->save();
        flash('Área registrada')->success();
        return redirect('areas');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $areas = Area::paginate(10);
        $editar = Area::find($id);
        return view('areas.index', compact('areas', 'editar'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $areaNueva = Area::find($id);
        $areaNueva->nombrearea = $request->nombre;
        $areaNueva->save();
        flash('Área actualizada')->success();
        return redirect('areas');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $temp = Area::where('id', $id)->first()->delete();
        flash('Área eliminada')->success();
        return redirect('areas');
    }
}
