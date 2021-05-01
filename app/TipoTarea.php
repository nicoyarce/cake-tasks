<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoTarea extends Model
{   
    use FechasTraducidas;
    protected $table = 'tipo_tareas';
    protected $dates = ['deleted_at', 'updated_at'];

    public function tareas(){
        return $this->hasMany(Tarea::class, 'tipo_tarea');
    }

    public function nomenclaturasAvances(){
        return $this->hasMany(NomenclaturaAvance::class, 'tipo_tarea');
    }

    protected static function boot() {
        //elimina nomenclaturas al eliminar tipo tarea
        parent::boot();
        static::deleting(function($tipoTarea) {
            if(!is_null($tipoTarea->nomenclaturasAvances())){
              $tipoTarea->nomenclaturasAvances()->delete();
            }            
        });
    }

    public function getHabilitaBorradoAttribute(){
        if (Tarea::where('tipo_tarea', '=', $this->id)->exists()) {
           return false;
        }
        else {
            return true;
        }
    }
}
