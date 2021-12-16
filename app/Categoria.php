<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Tarea;
use App\Proyecto;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categoria';
    protected $dates = ['deleted_at', 'updated_at'];

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function proyectos()
    {
        return $this->belongsToMany(Proyecto::class);
    }

    public function getHabilitaBorradoAttribute()
    {
        if (Tarea::where('categoria_id', $this->id)->exists()) {
            return false;
        } else {
            return true;
        }
    }
}
