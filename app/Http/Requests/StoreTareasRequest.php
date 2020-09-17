<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreTareasRequest extends FormRequest
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
        if (Auth::user()->can('crear_tareas') && Auth::user()->can('modificar_avance_tareas')) {
            return [
                'nombre' => 'required|min:4|max:100',
                'area_id' => 'required',
                'fecha_inicio' => 'required|date|before:fecha_termino_original',
                'fecha_termino_original' => 'required|date|after:fecha_inicio',
                'tipo_tarea' => 'required',
                'avance' => 'required'
            ];
        } elseif (Auth::user()->can('modificar_avance_tareas')) {
            return [
                'avance' => 'required'
            ];
        }
    }
}
