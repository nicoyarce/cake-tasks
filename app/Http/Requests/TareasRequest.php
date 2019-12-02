<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TareasRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre'=>'required|min:4|max:100',
            'area_id'=>'required',
            'fecha_inicio'=>'date|before:fecha_termino_original',            
            'fecha_termino_original'=>'required|date|after:fecha_inicio',           
            'fecha_termino'=>'nullable|date|after:fecha_termino_original',
            'tipo_tarea'=>'required',
            'avance'=>'required'           
        ];

    }
     
}
