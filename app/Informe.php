<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FechasTraducidas;
use Carbon\Carbon;
use App\Proyecto;

class Informe extends Model
{   
    use FechasTraducidas;
    protected $table = 'informes';

    public function proyecto(){
        return $this->belongsTo(Proyecto::class);
    }
}
