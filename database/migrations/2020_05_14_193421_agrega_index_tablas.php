<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregaIndexTablas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('areas', function (Blueprint $table) {
        //     $table->index('id');            
        // });
        // Schema::table('informes', function (Blueprint $table) {
        //     $table->index('id');
        //     //$table->foreign('proyecto_id')->references('id')->on('proyectos');
        // });
        // Schema::table('nomenclaturasavance', function (Blueprint $table) {
        //     $table->index('id');
        //     //$table->foreign('tipo_tarea')->references('id')->on('tipo_tareas');
        // });
        // Schema::table('observaciones', function (Blueprint $table) {
        //     $table->index('id');
        //     //$table->foreign('tarea_id')->references('id')->on('tareas');
        //     //$table->foreign('proyecto_id')->references('id')->on('proyectos');

        // });
        // Schema::table('proyectos', function (Blueprint $table) {
        //     $table->index('id');
        // });
        // Schema::table('tareas', function (Blueprint $table) {
        //     $table->index('id');
        //     //$table->foreign('area_id')->references('id')->on('areas');
        //     //$table->foreign('tipo_tarea')->references('id')->on('tipo_tareas');
        // });
        // Schema::table('tareas_hijas', function (Blueprint $table) {
        //     $table->index('id');
        //     //$table->foreign('tarea_madre_id')->references('id')->on('tareas');
        // });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
