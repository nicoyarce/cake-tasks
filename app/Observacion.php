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
    protected $appends = ['autor'];

    public function tarea()
    {
        return $this->belongsTo(Tarea::class, 'tarea_id');
    }

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class, 'proyecto_id');
    }

    public function autor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAutorAttribute()
    {
        $autor = User::where('id', $this->user_id)->get()->pluck('nombre');
        return $autor;
    }
}
