<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareaHija extends Model
{
    protected $table = 'tareas_hijas';
    protected $fillable = ['id','tarea_madre_id','nombre','fecha_inicio','fecha_termino','nivel','avance'];

    public function tareaMadre(){
        return $this->belongsTo(Tarea::class);
    }
}
