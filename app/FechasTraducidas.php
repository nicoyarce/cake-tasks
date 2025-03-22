<?php

namespace App;

use Carbon\Carbon;

trait FechasTraducidas
{

    public function getFechaAttribute($date)
    {
        return Carbon::parse($date)->locale('es');
    }

    public function getFechaInicioAttribute($date)
    {
        return Carbon::parse($date)->locale('es');
    }

    public function getFechaTerminoOriginalAttribute($date)
    {
        return Carbon::parse($date)->locale('es');
    }

    public function getFechaTerminoAttribute($date)
    {
        return Carbon::parse($date)->locale('es');
    }

    public function getFechaUltimoCambioFtrAttribute($date)
    {
        return Carbon::parse($date)->locale('es');
    }

    public function getFechaUltimoCambioFttAttribute($date)
    {
        return Carbon::parse($date)->locale('es');
    }

    public function getFechaUltimoCambioAvanceAttribute($date)
    {
        return Carbon::parse($date)->locale('es');
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->locale('es');
    }
}
