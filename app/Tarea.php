<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FechasTraducidas;
use Carbon\Carbon;
use App\PropiedadesGrafico;

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
    protected $fillable = ['proyecto_id', 'area_id', 'nombre', 'fecha_inicio', 'fecha_termino_original', 'fecha_termino', 'avance', 'critica', 'nro_documento', 'tipo_tarea'];
    protected $dates = ['deleted_at'];
    /*protected $casts = [
        'fecha_inicio' => 'date:Y-m-d',
        'fecha_termino_original' => 'date:Y-m-d',
        'fecha_termino' => 'date:Y-m-d'
    ];*/
    protected $appends = ['nombreArea', 'atraso', 'colorAtraso', 'porcentajeAtraso', 'observaciones', 'glosaAvance'];

    public function proyecto()
    {
        return $this->belongsTo(Proyecto::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function tareasHijas()
    {
        return $this->hasMany(TareaHija::class, 'tarea_madre_id')->withTrashed();
    }

    public function observaciones()
    {
        return $this->hasMany(Observacion::class, 'tarea_id')->withTrashed();
    }

    public function autorUltimoCambioFtt()
    {
        return $this->belongsTo(User::class, 'autor_ultimo_cambio_ftt_id');
    }

    public function autorUltimoCambioAvance()
    {
        return $this->belongsTo(User::class, 'autor_ultimo_cambio_avance_id');
    }

    public function tipoTarea()
    {
        return $this->belongsTo(TipoTarea::class, 'tipo_tarea');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    protected static function boot()
    {
        //elimina tareas hijas al eliminar tarea madre
        parent::boot();
        static::deleting(function ($tarea) {
            if (!is_null($tarea->tareasHijas())) {
                $tarea->tareasHijas()->delete();
            }
            if (!is_null($tarea->observaciones())) {
                $tarea->observaciones()->delete();
            }
        });

        static::restoring(function ($tarea) {
            if (!is_null($tarea->tareasHijas())) {
                $tarea->tareasHijas()->restore();
            }
            if (!is_null($tarea->observaciones())) {
                $tarea->observaciones()->restore();
            }
        });
    }

    public function getAtrasoAttribute()
    {
        $final = Carbon::parse($this->fecha_termino_original);
        return $final->diffInDays($this->fecha_termino);
    }

    public function getNombreAreaAttribute()
    {
        $nombreArea = Area::where('id', $this->area_id)->pluck('nombrearea');
        return $nombreArea;
    }

    public function getDuracionAttribute()
    {
        $final = Carbon::parse($this->fecha_termino);
        return $final->diffInDays($this->fecha_inicio);
    }

    /*Ojo, en esta funcion hay variables hardcoded o en duro*/
    public function getColorAtrasoAttribute()
    {
        $propiedades = session('propiedades_grafico_cache');
        // fechaAdvertencia corresponde al 60% y fechaPeligro al 90%
        $porcentajeParaVerde = $propiedades[5]->avance; // este valor indica bajo que avance la tarea cambiara a color verde
        $fechaInicioCarbon = Carbon::parse($this->fecha_inicio);
        $fechaTerminoCarbon = Carbon::parse($this->fecha_termino);
        $hoyCarbon = (is_null($this->deleted_at)) ? Carbon::today() : $this->deleted_at;
        $diasDeEjecucion = $fechaInicioCarbon->diffInDays($fechaTerminoCarbon); //indica la diferencia de dias entre el inicio y termino de la tarea
        $fechaAdvertencia = Carbon::parse($fechaInicioCarbon)->addDays(round($diasDeEjecucion * (3 / 5))); //indica la fecha en que se cumple un 60% del tiempo
        $fechaPeligro = Carbon::parse($fechaInicioCarbon)->addDays(round($diasDeEjecucion * (9 / 10))); //indica la fecha en que se cumple un 90% del tiempo
        if ($hoyCarbon->lte($fechaAdvertencia)) {
            return $propiedades[0]->color;
        } elseif ($hoyCarbon->gt($fechaAdvertencia) && $hoyCarbon->lte($fechaPeligro)) {
            if ($this->avance >= $porcentajeParaVerde) {
                return $propiedades[0]->color;
            }
            return $propiedades[1]->color;
        } elseif ($hoyCarbon->gt($fechaPeligro) && $hoyCarbon->lte($fechaTerminoCarbon)) {
            if ($this->avance >= $porcentajeParaVerde) {
                return $propiedades[0]->color;
            }
            return $propiedades[2]->color;
        } else {
            if ($this->avance >= $porcentajeParaVerde) {
                return $propiedades[0]->color;
            }
            return $propiedades[3]->color;
        }
    }

    /*Se usa para dibujar la linea en la flecha de avance del grafico*/
    public function getPorcentajeAtrasoAttribute()
    {
        $fechaInicioCarbon = Carbon::parse($this->fecha_inicio);
        $fechaTerminoCarbon = Carbon::parse($this->fecha_termino);
        $hoyCarbon = (is_null($this->deleted_at)) ? Carbon::today() : $this->deleted_at;
        $diasDeEjecucion = $fechaInicioCarbon->diffInDays($fechaTerminoCarbon);
        if ($hoyCarbon->lte($fechaInicioCarbon)) {
            $porcentajeAtraso = 0;
        } elseif ($hoyCarbon->gt($fechaInicioCarbon) && $hoyCarbon->lt($fechaTerminoCarbon)) {
            $diasHastaHoy = $fechaInicioCarbon->diffInDays($hoyCarbon);
            $porcentajeAtraso = round(($diasHastaHoy * 100) / $diasDeEjecucion); //regla de 3 para saber que porcentaje de atraso hay
        } else {
            $porcentajeAtraso = 100;
        }
        return $porcentajeAtraso;
    }

    public function getObservacionesAttribute()
    {
        $observaciones = Observacion::where('tarea_id', $this->id)->get();
        return $observaciones->toArray();
    }

    public function getGlosaAvanceAttribute()
    {
        $glosa = NomenclaturaAvance::where('porcentaje', $this->avance)->pluck('glosa');
        return $glosa;
    }

    public function getNombreAutorUltimoCambioFtt()
    {
        if (!empty($this->autorUltimoCambioFtt())) {
            return array_get($this->autorUltimoCambioFtt()->withTrashed()->first(), 'nombre');
        }
        return '';
    }

    public function getNombreAutorUltimoCambioAvance()
    {
        if (!empty($this->autorUltimoCambioAvance())) {
            return array_get($this->autorUltimoCambioAvance()->withTrashed()->first(), 'nombre');
        }
        return '';
    }

    public function scopeCompletadas($query)
    {
        return $query->where('avance', '=', '100');
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
