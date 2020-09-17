<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PropiedadesGrafico;
use Validator;
use Illuminate\Validation\Rule;

class PropiedadesGraficoController extends Controller
{
    public function index()
    {   
        $propiedades = PropiedadesGrafico::all();
        return view('propiedadesGrafico')->with('propiedades', $propiedades);
    }

    public function indexConModal()
    {   
        $abrir_modal = 1;
        $propiedades = PropiedadesGrafico::all();
        return view('propiedadesGrafico')->with('propiedades', $propiedades);
    }
    
    public function edit($id)
    {   
        $propiedades = PropiedadesGrafico::all();
        $editar = PropiedadesGrafico::find($id);
        return view('propiedadesGrafico', compact('propiedades', 'editar'));
    }

    public function update(Request $request, $id)
    {                   
        $editar = PropiedadesGrafico::find($id);
        $validatedData = Validator::make($request->all(), 
            [
                'nombre' => 'string',            
                'avance' => [                    
                    'numeric',
                    'min:0',
                    'max:100',
                    Rule::unique('propiedades_grafico')->ignore($editar->id)
                ],
                [
                'avance.unique' => 'El número del avance no puede repetirse'
                ]
            ]
        )->validate();
        $editar->nombre = $request->nombre;
        if ($request->has('color')) {
            $editar->color = $request->color;
        }        
        $editar->save();             
        flash('Propiedad actualizada')->success();
        return redirect('propiedadesGrafico');
    }

    public function obtienePropiedadesGrafico()
    {
        return json_encode(PropiedadesGrafico::all());
    }
    
}
