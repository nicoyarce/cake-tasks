<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePropiedadesGrafico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('propiedades_grafico', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombre');
            $table->integer('avance');
            $table->string('color');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('propiedades_grafico');
    }
}
