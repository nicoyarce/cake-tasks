<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregaFechasUltimoCambio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proyectos', function (Blueprint $table) {
            $table->unsignedInteger('autor_ultimo_cambio_ftr_id')->nullable();
            $table->dateTime('fecha_ultimo_cambio_ftr')->nullable();            
        });
        Schema::table('tareas', function (Blueprint $table) {
            $table->unsignedInteger('autor_ultimo_cambio_ftt_id')->nullable();
            $table->unsignedInteger('autor_ultimo_cambio_avance_id')->nullable();
            $table->dateTime('fecha_ultimo_cambio_ftt')->nullable();
            $table->dateTime('fecha_ultimo_cambio_avance')->nullable();
        });
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
