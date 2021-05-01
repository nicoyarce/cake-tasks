<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProyectoUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::enableForeignKeyConstraints();
        Schema::create('proyecto_user', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('proyecto_id');
            $table->unsignedInteger('user_id');
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
        Schema::dropIfExists('proyecto_user');
    }
}
