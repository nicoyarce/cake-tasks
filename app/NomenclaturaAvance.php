<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NomenclaturaAvance extends Model
{
    use FechasTraducidas;
    protected $table = 'nomenclaturas_avance';

    public function tipoTarea(){
        return $this->belongsTo(TipoTarea::class, 'tipo_tarea');
    }

    public function getHabilitaBorradoAttribute(){
    	if (Tarea::where('avance', '=', $this->porcentaje)->exists()) {
		   return false;
		}
		else {
			return true;
		}
    }
}
