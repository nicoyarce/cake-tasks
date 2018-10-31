<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TareaHija extends Model
{
    protected $fillable = ['tarea_id','nombre','fecha_inicio','fecha_termino','avance','observaciones'];
}
