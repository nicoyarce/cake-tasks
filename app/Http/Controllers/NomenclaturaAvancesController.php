<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;
use App\Http\Controllers\Controller;
use App\NomenclaturaAvance;
use App\TipoTarea;

class NomenclaturaAvancesController extends Controller
{

    public function index(Request $request)
    {
        $tipo_tarea = $request->id;
        $tipo_tarea = TipoTarea::find($tipo_tarea);        
        $tipos_avance = $tipo_tarea->nomenclaturasAvances()->get()->sortBy('porcentaje');
        return view('avances.index', compact('tipo_tarea', 'tipos_avance'));
    }

    public function indexConModal(Request $request)
    {   
        $abrir_modal = 1;
        $tipo_tarea = $request->id;
        $tipo_tarea = TipoTarea::find($tipo_tarea);
        $tipos_avance = $tipo_tarea->nomenclaturasAvances()->get()->sortBy('porcentaje');
        return view('avances.index', compact('tipo_tarea', 'tipos_avance'));
    }    

    public function store(Request $request)
    {   
        $porcentaje = $request->porcentaje;
        $tipo_tarea_id = $request->tipo_tarea_id;        
        $validatedData = Validator::make($request->all(), 
            [
                'glosa' => 'string',            
                'porcentaje' => [                    
                    Rule::unique('nomenclaturasAvance')->where(function ($query) use($porcentaje, $tipo_tarea_id) {
                        return $query->where('porcentaje', $porcentaje)->where('tipo_tarea', $tipo_tarea_id);
                    }),
                    'required',
                    'numeric',
                    'min:0',
                    'max:100'
                ]
            ],            
                [
                    'porcentaje.unique' => 'El número del porcentaje no puede repetirse'
                ]
        )->validate();
        $tipo_tarea = TipoTarea::find($request->tipo_tarea_id);
        $tipo_avance = new NomenclaturaAvance;
        $tipo_avance->glosa = $request->glosa;
        $tipo_avance->porcentaje = $request->porcentaje;
        $tipo_avance->tipoTarea()->associate($tipo_tarea);
        $tipo_avance->save();
        flash('Nomenclatura avance registrada')->success();
        return redirect()->route('avances.index', ['id' => $request->tipo_tarea_id]);
    }
    
    public function edit($id)
    {           
        $editar = NomenclaturaAvance::find($id);
        $tipo_tarea = $editar->tipoTarea()->get()->first();
        $tipos_avance = $tipo_tarea->nomenclaturasAvances()->get()->sortBy('porcentaje');               
        return view('avances.index', compact('tipos_avance', 'editar', 'tipo_tarea'));
    }

    public function update(Request $request, $id)
    {
        $porcentaje = $request->porcentaje;
        $tipo_tarea_id = $request->tipo_tarea_id;
        $validatedData = Validator::make($request->all(), 
            [
                'glosa' => 'string',            
                'porcentaje' => [                    
                    Rule::unique('nomenclaturasAvance')->where(function ($query) use($porcentaje, $tipo_tarea_id) {
                        return $query->where('porcentaje', $porcentaje)->where('tipo_tarea', $tipo_tarea_id);
                    })->ignore($id),
                    'required',
                    'numeric',
                    'min:0',
                    'max:100'
                ]
            ],            
                [
                    'porcentaje.unique' => 'El número del porcentaje no puede repetirse'
                ]
        )->validate();   
        $tipo_tarea = TipoTarea::find($request->tipo_tarea_id);         
        $tipo_avance_nuevo = NomenclaturaAvance::find($id);
        $tipo_avance_nuevo->glosa = $request->glosa;
        $tipo_avance_nuevo->porcentaje = $request->porcentaje;
        $tipo_avance_nuevo->tipoTarea()->associate($tipo_tarea);
        $tipo_avance_nuevo->save();              
        flash('Nomenclatura avance actualizada')->success();
        return redirect()->route('avances.index', ['id' => $request->tipo_tarea_id]);
    }

    public function destroy($id)
    {
        $eliminar = NomenclaturaAvance::find($id);        
        $tipo_tarea = $eliminar->tipoTarea()->get()->first()->id;
        NomenclaturaAvance::find($id)->delete();        
        flash('Nomenclatura avance eliminada')->success(); 
        return redirect()->route('avances.index', ['id' => $tipo_tarea]);
    }

    public function avances(Request $request) {
        $tipo_tarea = TipoTarea::find($request->tipo_tarea);
        $tipos_avance = $tipo_tarea->nomenclaturasAvances()->get()->sortBy('porcentaje')->toArray();
        return response()->json(array_values($tipos_avance));
    }
}

