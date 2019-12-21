<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProyectosRequest extends FormRequest
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
            'nombre'=>'string|required|min:4|max:100',
            'fecha_inicio'=>'date|before:fecha_termino_original',
            'fecha_termino_original'=>'required|date|after:fecha_inicio|before:fecha_termino',
            'fecha_termino'=>'nullable|date|after:fecha_termino_original',
        ];
    }
}
