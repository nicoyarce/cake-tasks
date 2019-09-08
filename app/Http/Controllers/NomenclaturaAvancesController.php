<?php

namespace App\Http\Controllers;

use App\NomenclaturaAvance;
use Illuminate\Http\Request;

class NomenclaturaAvancesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $tipos_avance = NomenclaturaAvance::paginate(10);
        return view('avances.index')->with('tipos_avance', $tipos_avance);
    }

    public function indexConModal()
    {   
        $abrir_modal = 1;
        $tipos_avance = NomenclaturaAvance::paginate(10);
        return view('avances.index', compact('tipos_avance', 'abrir_modal'));
    }    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tipo_avance = new NomenclaturaAvance;
        $tipo_avance->glosa = $request->glosa;
        $tipo_avance->porcentaje = $request->porcentaje;
        $tipo_avance->save();
        flash('Nomenclatura registrada')->success();
        return redirect('avances');
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $tipos_avance = NomenclaturaAvance::paginate(10);
        $editar = NomenclaturaAvance::find($id);
        return view('avances.index', compact('tipos_avance', 'editar'));
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
        $tipo_avance_nuevo = NomenclaturaAvance::find($id);
        $tipo_avance_nuevo->glosa = $request->glosa;
        $tipo_avance_nuevo->porcentaje = $request->porcentaje;
        $tipo_avance_nuevo->save();             
        flash('Nomenclatura actualizada')->success();
        return redirect('avances');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Area  $area
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $temp = NomenclaturaAvance::where('id', $id)->first()->delete();        
        flash('Nomenclatura eliminada')->success(); 
        return redirect('areas');
    }
}
