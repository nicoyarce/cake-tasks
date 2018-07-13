<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tarea extends Model
{
    protected $table = 'tareas';
    protected $fillable = ['nombre','proyecto_id','fechainicio','fechatermino','avance'];

    public static function sacarDatos(){
        $tareas = Tarea::all();
        if($tareas->count() == 0){
            return 0;
        }
        $tareas->makeHidden('created_at');
        $tareas->makeHidden('updated_at');
        $tareas->makeHidden('fechainicio');        
        return $tareas->toJson();
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class);
    }
}
