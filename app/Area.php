<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FechasTraducidas;
use App\Tarea;

class Area extends Model
{
    use FechasTraducidas;
    
    protected $table = 'areas';
    protected $fillable = ['area'];

    public function tarea()
    {
        return $this->hasMany(Tarea::class);
    }

    public function getHabilitaBorradoAttribute()
    {
        if (Tarea::where('area_id', '=', $this->id)->exists()) {
            return false;
        } else {
            return true;
        }
    }
}
