<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FechasTraducidas;
use Carbon\Carbon;
use App\Proyecto;

class Informe extends Model
{   
    use SoftDeletes;
    use FechasTraducidas;
    protected $table = 'informes';
    protected $dates = ['deleted_at'];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class);
    }
}
