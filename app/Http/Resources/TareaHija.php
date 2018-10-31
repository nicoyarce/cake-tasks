<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TareaHija extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return[            
            'id'            => (string) "-"+$this->id,
            'name'            => $this->nombre,
            'progress'            => $this->avance,
            'level'            => $this->nivel,
            //'depends'            => $this->id,
            'start'            => $this->fecha_inicio,
            //'duration'            => (string) "-"+$this->id,
            'end'            => $this->fecha_termino,
            'collapsed'            => false,
            'hasChild'            => false
            // 'attributes'    => [
            //     'title' => $this->title,
            // ],
        ];
    }
}
