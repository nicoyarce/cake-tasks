<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenombrarColumnaTrabajoExterno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tareas', function(Blueprint $table) {
            $table->renameColumn('trabajo_externo', 'trabajo_interno');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tareas', function(Blueprint $table) {
            $table->renameColumn('trabajo_interno', 'trabajo_externo');
        });
    }
}
