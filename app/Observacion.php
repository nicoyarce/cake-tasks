<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Tarea;

class Observacion extends Model
{
    use SoftDeletes;
    protected $table = 'observaciones';
    protected $dates = ['deleted_at'];

    public function tarea(){
        return $this->belongsTo(Tarea::class);
    }

}
