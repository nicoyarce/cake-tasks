<?php

namespace App;

use Carbon\Carbon;

trait FechasTraducidas
{

    public function getFechaAttribute($date)
    {
        return Carbon::parse($date);
    }

    public function getFechaInicioAttribute($date)
    {
        return Carbon::parse($date);
    }

    public function getFechaTerminoOriginalAttribute($date)
    {
        return Carbon::parse($date);
    }

    public function getFechaTerminoAttribute($date)
    {
        return Carbon::parse($date);
    }

    public function getFechaUltimoCambioFtrAttribute($date)
    {
        return Carbon::parse($date);
    }

    public function getFechaUltimoCambioFttAttribute($date)
    {
        return Carbon::parse($date);
    }

    public function getFechaUltimoCambioAvanceAttribute($date)
    {
        return Carbon::parse($date);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date);
    }
}
