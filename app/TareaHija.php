<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FechasTraducidas;
use Carbon\Carbon;

class TareaHija extends Model
{
    use FechasTraducidas;
    protected $table = 'tareas_hijas';
    protected $fillable = ['id','tarea_madre_id','nombre','fecha_inicio','fecha_termino','nivel','avance'];

    public function tareaMadre(){
        return $this->belongsTo(Tarea::class);
    }
}
