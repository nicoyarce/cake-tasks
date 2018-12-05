<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FechasTraducidas;
use Carbon\Carbon;
use App\Tarea;
use App\User;
use App\Informe;
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
    use SoftDeletes;
    use FechasTraducidas;
    protected $table = 'proyectos';
    protected $fillable = ['nombre','fecha_inicio','fecha_termino_original','fecha_termino','avance'];
    protected $dates = ['deleted_at'];

    public function tareas(){
        return $this->hasMany(Tarea::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function informes(){
        return $this->hasMany(Informe::class);
    }

    protected static function boot() {
        parent::boot();
        static::deleting(function($proyecto) { 
            foreach($proyecto->tareas as $tarea){
              $tarea->delete();
            }
            foreach($proyecto->informes as $informe){
              $informe->delete();
            }
        });
    }

    public function getAtrasoAttribute(){
        $final = Carbon::parse($this->fecha_termino_original);
        return $final->diffInDays($this->fecha_termino);
    }

    public function getAvanceAttribute(){
        $tareas = Tarea::where('proyecto_id',$this->id)->get();
        if(count($tareas) == 0){
            return 0;
        }
        else{
            $totalDuracion = 0;
            foreach ($tareas as $tarea) {
                $totalDuracion = $totalDuracion + $tarea->duracion;
            } 
            $tiempoPonderado = 0;
            $avancePonderado = 0;
            foreach ($tareas as $tarea) {
                $tiempoPonderado = $tarea->duracion/$totalDuracion;
                $avancePonderado = $avancePonderado + ($tarea->avance * $tiempoPonderado);
            }
            return floor($avancePonderado);
        }        
    }

    public function getColorAtrasoAttribute(){
        $fechaInicioCarbon = Carbon::parse($this->fecha_inicio);
        $fechaTerminoOrigCarbon = Carbon::parse($this->fecha_termino);
        $hoyCarbon = Carbon::today();
        $diferenciaFechas = $fechaInicioCarbon->diffInDays($fechaTerminoOrigCarbon);        
        $fechaAdvertencia = $fechaInicioCarbon->addDays(($diferenciaFechas*60)/100); // verde antes de esta fecha
        $fechaPeligro = Carbon::parse($this->fecha_inicio)->addDays(($diferenciaFechas*90)/100);  // amarillo antes de esta fecha, naranjo despues de fecha 
        if($hoyCarbon->lte($fechaAdvertencia)){
            return "VERDE";
        }
        else if($hoyCarbon->gt($fechaAdvertencia) && $hoyCarbon->lte($fechaPeligro)){
            return "AMARILLO";
        }
        else if($hoyCarbon->gt($fechaPeligro) && $hoyCarbon->lte($fechaTerminoOrigCarbon)){
            return "NARANJO";
        }
        else{
            return "ROJO";
        }
    }
}
