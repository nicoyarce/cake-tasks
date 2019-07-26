<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    use SoftDeletes;
    use FechasTraducidas;
    protected $table = 'tareas';
    protected $fillable = ['proyecto_id','area_id','nombre','fecha_inicio','fecha_termino_original','fecha_termino','avance','critica'];
    protected $dates = ['deleted_at'];
    /*protected $casts = [
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_termino_original' => 'date:Y-m-d',
        'fecha_termino' => 'date:Y-m-d'
    ];*/
    protected $appends = ['nombreArea', 'atraso', 'colorAtraso','porcentajeAtraso','observaciones'];

    public function proyecto(){
        return $this->belongsTo(Proyecto::class);
    }

    public function area(){
        return $this->belongsTo(Area::class);
    }

    public function tareasHijas(){
        return $this->hasMany(TareaHija::class, 'tarea_madre_id')->withTrashed();
    }

    public function observaciones(){
        return $this->hasMany(Observacion::class)->withTrashed();
    }

    protected static function boot() {
        //elimina tareas hijas al eliminar tarea madre
        parent::boot();
        static::deleting(function($tarea) {
            foreach($tarea->tareasHijas as $tareaHija){
              $tareaHija->delete();
            }
            foreach($tarea->observaciones as $observacion){
              $observacion->delete();
            }
        });
    }

    public function getAtrasoAttribute(){
        $final = Carbon::parse($this->fecha_termino_original);
        return $final->diffInDays($this->fecha_termino);
    }

    public function getNombreAreaAttribute(){
        $nombreArea = Area::where('id',$this->area_id)->pluck('nombrearea');
        return $nombreArea;
    }

    public function getDuracionAttribute(){
        $final = Carbon::parse($this->fecha_termino);
        return $final->diffInDays($this->fecha_inicio);
    }

    public function getColorAtrasoAttribute(){
        // fechaAdvertencia corresponde al 60% y fechaPeligro al 90%
        $porcentajeParaVerde = 80; // este valor indica bajo que avance la tarea cambiara a color verde
        $fechaInicioCarbon = Carbon::parse($this->fecha_inicio);
        $fechaTerminoCarbon = Carbon::parse($this->fecha_termino);
        $hoyCarbon = Carbon::today();
        $diasDeEjecucion = $fechaInicioCarbon->diffInDays($fechaTerminoCarbon); //indica la diferencia de dias entre el inicio y termino de la tarea
        $fechaAdvertencia = Carbon::parse($fechaInicioCarbon)->addDays(round($diasDeEjecucion*(3/5))); //indica la fecha en que se cumple un 60% del tiempo
        $fechaPeligro = Carbon::parse($fechaInicioCarbon)->addDays(round($diasDeEjecucion*(9/10))); //indica la fecha en que se cumple un 90% del tiempo
        if($hoyCarbon->lte($fechaAdvertencia)){
            return "VERDE";
        }
        else if($hoyCarbon->gt($fechaAdvertencia) && $hoyCarbon->lte($fechaPeligro)){
            if($this->avance >= $porcentajeParaVerde){
                return "VERDE";
            }
            return "AMARILLO";
        }
        else if($hoyCarbon->gt($fechaPeligro) && $hoyCarbon->lte($fechaTerminoCarbon)){
            if($this->avance >= $porcentajeParaVerde){
                return "VERDE";
            }
            return "NARANJO";
        }
        else{
            if($this->avance >= $porcentajeParaVerde){
                return "VERDE";
            }
            return "ROJO";
        }
    }

    /*Se usa para dibujar la linea en la flecha de avance del grafico*/
    public function getPorcentajeAtrasoAttribute(){
        $fechaInicioCarbon = Carbon::parse($this->fecha_inicio);
        $fechaTerminoCarbon = Carbon::parse($this->fecha_termino);
        $hoyCarbon = Carbon::today();
        $diasDeEjecucion = $fechaInicioCarbon->diffInDays($fechaTerminoCarbon);
        if($hoyCarbon->lte($fechaInicioCarbon)){
            $porcentajeAtraso = 0;
        }
        else if($hoyCarbon->gt($fechaInicioCarbon) && $hoyCarbon->lt($fechaTerminoCarbon)){
            $diasHastaHoy = $fechaInicioCarbon->diffInDays($hoyCarbon);
            $porcentajeAtraso = round(($diasHastaHoy*100)/$diasDeEjecucion); //regla de 3 para saber que porcentaje de atraso hay
        }
        else{
            $porcentajeAtraso = 100;
        }
        return $porcentajeAtraso;
    }

    public function getObservacionesAttribute(){
        $observaciones = Observacion::where('tarea_id',$this->id)->pluck('contenido');
        return $observaciones;
    }

    public function scopeAtrasoVerde($query){
        return $query->where('colorAtraso','VERDE');
    }

    public function scopeCompletadas($query){
        return $query->where('avance','=','100');
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
