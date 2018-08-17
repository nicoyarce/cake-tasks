<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\FechasTraducidas;
use Carbon\Carbon;
/**
 * App\Tarea
 *
 * @property-read \App\Proyecto $proyecto
 * @mixin \Eloquent
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $proyecto_id
 * @property string $nombre
 * @property string $fecha_inicio
 * @property string $fecha_termino
 * @property int $avance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tarea whereAvance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tarea whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tarea whereFecha_inicio($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tarea whereFecha_termino($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tarea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tarea whereNombre($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tarea whereProyectoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Tarea whereUpdatedAt($value)
 */
class Tarea extends Model
{
    use FechasTraducidas;
    protected $table = 'tareas';
    protected $fillable = ['proyecto_id','area_id','nombre','fecha_inicio','fecha_termino_original','fecha_termino','avance'];
    /*protected $casts = [
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_termino_original' => 'date:Y-m-d',
        'fecha_termino' => 'date:Y-m-d'
    ];*/
    protected $appends = ['nombreArea', 'atraso', 'colorAtraso', "open"];    
    
    public function getOpenAttribute(){
        return true;    
    }

    public function proyecto(){
        return $this->belongsTo(Proyecto::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function getAtrasoAttribute($atraso){
        $final = Carbon::parse($this->fecha_termino_original);        
        return $final->diffInDays($this->fecha_termino);
    }

    public function getNombreAreaAttribute(){
        $nombreArea = Area::where('id',$this->area_id)->pluck('nombrearea');
        return $nombreArea;
    }

    public function getColorAtrasoAttribute(){
        $fechaInicioCarbon = Carbon::parse($this->fecha_inicio);
        $fechaTerminoOrigCarbon = Carbon::parse($this->fecha_termino);
        $hoyCarbon = Carbon::today();
        $diferenciaFechas = $fechaInicioCarbon->diffInDays($fechaTerminoOrigCarbon);        
        $mitad = $fechaInicioCarbon->addDays($diferenciaFechas/2);
        $fechaAdvertencia = Carbon::parse($this->fecha_inicio)->addDays($diferenciaFechas*(3/4));
        if($hoyCarbon->lte($mitad)){
            return "VERDE";
        }
        else if($hoyCarbon->gt($mitad) && $hoyCarbon->lte($fechaAdvertencia)){            
            return "AMARILLO";
        }
        else if($hoyCarbon->gt($fechaAdvertencia) && $hoyCarbon->lte($fechaTerminoOrigCarbon)){
            return "NARANJO";
        }
        else{
            return "ROJO";
        }
    }
/*
    public function getFechaInicioAttribute($atraso){
        return $this->fecha_inicio->toDateTimeString(); 
    }
    public function getFechaTerminoOriginalAttribute($atraso){    
        return $this->fecha_termino_original->toDateTimeString();
    }
    public function getFechaTerminoAttribute($atraso){
        return $this->fecha_termino->toDateTimeString(); 
    }*/
}
