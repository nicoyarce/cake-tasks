<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categoria', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::table('tareas', function (Blueprint $table) {
            $table->unsignedInteger('categoria_id')->nullable();
        });

        Schema::enableForeignKeyConstraints();
        Schema::create('categoria_proyecto', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proyecto_id');
            $table->unsignedInteger('categoria_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categoria');
        Schema::table('tareas', function (Blueprint $table) {
            $table->dropColumn('categoria_id');
        });
        Schema::dropIfExists('categoria_proyecto');
    }

}
