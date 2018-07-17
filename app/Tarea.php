<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    protected $table = 'tareas';
    protected $fillable = ['proyecto_id','area_id','nombre','fecha_inicio','fecha_termino','avance'];
   
    public function proyecto(){
        return $this->belongsTo(Proyecto::class);
    }

    public function area(){
        return $this->hasOne(Area::class);
    }

    public static function sacarDatos($id){
        if($id==0){
            $tareas = Tarea::all();
        }
        else{
            $tareas = Tarea::where('area_id', $id)->get();
        }
        if($tareas->count() == 0){
            return 0;
        }
        $tareas->makeHidden('area_id');
        $tareas->makeHidden('proyecto_id');        
        $tareas->makeHidden('created_at');
        $tareas->makeHidden('updated_at');
        $tareas->makeHidden('fecha_inicio');        
        return $tareas->toJson();
    }
}
