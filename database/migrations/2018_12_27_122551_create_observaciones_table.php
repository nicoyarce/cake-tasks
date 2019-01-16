<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Tarea;
use App\Observacion;

class CreateObservacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('observaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tarea_id');
            $table->text('contenido')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        /*
        $tareas = Tarea::all();

        foreach ($tareas as $tarea) {
            if(!is_null($tarea->observaciones)){
                $observacion = new Observacion();
                $observacion->contenido = $tarea->observaciones;
                $observacion->tarea()->associate($tarea);
                $observacion->save();
            }
        }
        */

        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('observaciones');
        Schema::table('tareas', function (Blueprint $table) {
            $table->text('observaciones');
        });
    }
}
