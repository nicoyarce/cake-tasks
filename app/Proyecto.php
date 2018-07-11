<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $table = 'proyectos';
    protected $fillable = ['nombre','fechainicio','fechatermino','avance'];

    public function tarea(){
        return $this->hasMany(Tareas::Class);
    }
}
