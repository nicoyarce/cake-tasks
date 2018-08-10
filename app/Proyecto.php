<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FechasTraducidas;
use Carbon\Carbon;
use App\Tarea;
use App\User;
/**
 * App\Proyecto
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tarea[] $tareas
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $nombre
 * @property string $fecha_inicio
 * @property string $fecha_termino
 * @property int $avance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proyecto whereAvance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proyecto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proyecto whereFecha_inicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proyecto whereFecha_termino($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proyecto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proyecto whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Proyecto whereUpdatedAt($value)
 */
class Proyecto extends Model
{
    use FechasTraducidas;
    protected $table = 'proyectos';
    protected $fillable = ['nombre','fecha_inicio','fecha_termino_original','fecha_termino','avance'];

    public function tareas(){
        return $this->hasMany(Tarea::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function getAtrasoAttribute($atraso){
        $final = Carbon::parse($this->fecha_termino_original);
        if($final->diffInDays($this->fecha_termino, false) == 0){
            return $final->diffInDays(Carbon::today(), false);
        }
        else{
            return $final->diffInDays($this->fecha_termino, false);    
        }        
    }

    public function getAvanceAttribute($avance){
        $tareas = Tarea::where('proyecto_id',$this->id)->get();
        $total = 0;
        foreach ($tareas as $tarea) {
            $total = $total + $tarea->avance;
        }
        if(count($tareas) == 0){
            return 0;
        }
        else{
            return floor($total/count($tareas));
        }
        
    }
}
