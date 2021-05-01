<?php

namespace App;

use Jenssegers\Date\Date;

trait FechasTraducidas{

    public function getFechaAttribute($date){
        return new Date($date);
    }

    public function getFechaInicioAttribute($date){
        return new Date($date);
    }

    public function getFechaTerminoOriginalAttribute($date){
        return new Date($date);
    }

    public function getFechaTerminoAttribute($date){
        return new Date($date);
    }

    public function getFechaUltimoCambioFtrAttribute($date){
        return new Date($date);
    }

    public function getFechaUltimoCambioFttAttribute($date){
        return new Date($date);
    }

    public function getFechaUltimoCambioAvanceAttribute($date){
        return new Date($date);
    }

    public function getCreatedAtAttribute($date){
        return new Date($date);
    }
}
