<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\FechasTraducidas;
use Carbon\Carbon;
use App\Tarea;
use App\TareaHija;
use App\User;
use App\Informe;
use App\Categoria;

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
    protected $fillable = ['nombre', 'fecha_inicio', 'fecha_termino_original', 'fecha_termino', 'avance'];
    protected $dates = ['deleted_at'];

    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    public function tareasArchivadas()
    {
        return $this->hasMany(Tarea::class)->withTrashed();
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function informes()
    {
        return $this->hasMany(Informe::class);
    }

    public function tareasHijas()
    {
        return $this->hasManyThrough(TareaHija::class, Tarea::class, 'proyecto_id', 'tarea_madre_id', 'id', 'id')->withTrashed();
    }

    public function observaciones()
    {
        return $this->hasMany(Observacion::class, 'proyecto_id')->withTrashed();
    }

    public function autorUltimoCambioFtr()
    {
        return $this->belongsTo(User::class, 'autor_ultimo_cambio_ftr_id');
    }

    public function categorias()
    {
        return $this->belongsToMany(Categoria::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($proyecto) {
            if (!is_null($proyecto->tareas())) {
                foreach ($proyecto->observaciones()->get() as $item) {
                    $item->delete();
                }
                foreach ($proyecto->tareas()->get() as $item) {
                    $item->delete();
                }
                $proyecto->tareas()->delete();
            }
            if (!is_null($proyecto->informes())) {
                $proyecto->informes()->delete();
            }
            if (!is_null($proyecto->observaciones())) {
                $proyecto->observaciones()->delete();
            }
        });

        static::restoring(function ($proyecto) {
            if (!is_null($proyecto->tareas())) {
                foreach ($proyecto->tareas()->get() as $item) {
                    $item->withTrashed()->restore();
                }
                foreach ($proyecto->tareas()->get() as $item) {
                    $item->withTrashed()->restore();
                }
                $proyecto->tareas()->restore();
            }
            if (!is_null($proyecto->informes())) {
                $proyecto->informes()->restore();
            }
            if (!is_null($proyecto->observaciones())) {
                $proyecto->observaciones()->restore();
            }
        });
    }

    public function getAtrasoAttribute()
    {
        $final = Carbon::parse($this->fecha_termino_original);
        return $final->diffInDays($this->fecha_termino);
    }

    public function getAvanceAttribute()
    {
        $tareas = (is_null($this->deleted_at)) ? $this->tareas : $this->tareasArchivadas;
        if (count($tareas) == 0) {
            return 0;
        } else {
            $totalDuracion = 0;
            foreach ($tareas as $tarea) {
                $totalDuracion += $tarea->duracion; //dias duracion
            }
            $tiempoPonderado = 0;
            $avancePonderado = 0;
            foreach ($tareas as $tarea) {
                $tiempoPonderado = $tarea->duracion / $totalDuracion;
                $avancePonderado = $avancePonderado + ($tarea->avance * $tiempoPonderado);
            }
            return floor($avancePonderado);
        }
    }

    public function getColorAtrasoAttribute()
    {
        $fechaInicioCarbon = Carbon::parse($this->fecha_inicio);
        $fechaTerminoOrigCarbon = Carbon::parse($this->fecha_termino);
        $hoyCarbon = (is_null($this->deleted_at)) ? Carbon::today() : $this->deleted_at;
        $diferenciaFechas = $fechaInicioCarbon->diffInDays($fechaTerminoOrigCarbon);
        $fechaAdvertencia = $fechaInicioCarbon->addDays(($diferenciaFechas * 60) / 100); // verde antes de esta fecha
        $fechaPeligro = Carbon::parse($this->fecha_inicio)->addDays(($diferenciaFechas * 90) / 100);  // amarillo antes de esta fecha, naranjo despues de fecha
        if ($hoyCarbon->lte($fechaAdvertencia)) {
            return "VERDE";
        } elseif ($hoyCarbon->gt($fechaAdvertencia) && $hoyCarbon->lte($fechaPeligro)) {
            return "AMARILLO";
        } elseif ($hoyCarbon->gt($fechaPeligro) && $hoyCarbon->lte($fechaTerminoOrigCarbon)) {
            return "NARANJO";
        } else {
            return "ROJO";
        }
    }

    public function getPorcentajeAtrasoAttribute()
    {
        $tareas = (is_null($this->deleted_at)) ? $this->tareas : $this->tareasArchivadas;
        if (count($tareas) == 0) {
            return 0;
        } else {
            $totalDuracion = 0;
            foreach ($tareas as $tarea) {
                $totalDuracion = $totalDuracion + $tarea->duracion; //dias duracion
            }
            $tiempoPonderado = 0;
            $avanceProyectado = 0;
            foreach ($tareas as $tarea) {
                $tiempoPonderado = $tarea->duracion / $totalDuracion;
                $avanceProyectado = $avanceProyectado + ($tarea->porcentajeAtraso * $tiempoPonderado);
            }
            return floor($avanceProyectado);
        }
    }
}
