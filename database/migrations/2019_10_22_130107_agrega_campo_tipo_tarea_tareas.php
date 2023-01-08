<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregaCampoTipoTareaTareas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->unsignedInteger('tipo_tarea')->nullable();
        });

        Schema::table('nomenclaturas_avance', function (Blueprint $table) {
            $table->unsignedInteger('tipo_tarea')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('tipo_tarea');
        });

        Schema::table('nomenclaturas_avance', function (Blueprint $table) {
            $table->dropColumn('tipo_tarea');
        });
    }
}
