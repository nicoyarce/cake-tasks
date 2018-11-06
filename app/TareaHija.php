<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareaHija extends Model
{
    protected $table = 'tareas_hijas';
    protected $fillable = ['id','nombre','fecha_inicio','fecha_termino','nivel','avance'];
}
