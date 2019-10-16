<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregarObservacionProyecto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('observaciones', function (Blueprint $table) {
            $table->unsignedInteger('tarea_id')->nullable()->change();
            $table->unsignedInteger('proyecto_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('observaciones', function (Blueprint $table) {
            $table->dropColumn('tarea_id');
            $table->dropColumn('proyecto_id');
        });
    }
}
