<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FechasTraducidas;
use Carbon\Carbon;

class TareaHija extends Model
{
    use SoftDeletes;
    use FechasTraducidas;
    protected $table = 'tareas_hijas';
    protected $fillable = ['id','tarea_madre_id','nombre','fecha_inicio','fecha_termino','nivel','avance'];
    protected $dates = ['deleted_at'];

    public function tareaMadre(){
        return $this->belongsTo(Tarea::class);
    }
}
