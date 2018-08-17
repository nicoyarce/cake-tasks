<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::create('tareas', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->unsignedInteger('id_madre')->nullable()->default(0);
            $table->unsignedInteger('proyecto_id');
            $table->unsignedInteger('area_id');
            $table->string('nombre');
            $table->date('fecha_inicio');
            $table->date('fecha_termino_original');
            $table->date('fecha_termino');            
            $table->integer('avance')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tareas');
    }
}
