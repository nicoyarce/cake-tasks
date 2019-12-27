<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateTareasRequest extends FormRequest
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
        if (Auth::user()->hasRole('Usuario')) {
            return [
                'avance' => 'required'
            ];
        }
        if (Auth::user()->hasRole('Administrador')) {
            return [
                'nombre' => 'required|min:4|max:100',
                'area_id' => 'required',
                'fecha_inicio' => 'required|date|before:fecha_termino_original',
                'fecha_termino_original' => 'required|date|after:fecha_inicio|before:fecha_termino',
                'fecha_termino' => 'nullable|date|after:fecha_termino_original',
                'tipo_tarea' => 'required',
                'avance' => 'required'
            ];
        }
        if (Auth::user()->hasRole('OCR')) {
            return [
                'nombre' => 'required|min:4|max:100',
                'area_id' => 'required',
                'fecha_termino' => 'date|after:fecha_termino_original',
                'tipo_tarea' => 'required',
                'avance' => 'required'
            ];
        }
    }
}
