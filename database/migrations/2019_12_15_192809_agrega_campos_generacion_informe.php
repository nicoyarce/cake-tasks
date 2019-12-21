<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AgregaCamposGeneracionInforme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('informes', function (Blueprint $table) {
            $table->boolean('grafico')->default(true);
            $table->boolean('observaciones')->default(true);
            $table->json('colores')->default('{"0": "#28a745", "1": "#ffff00", "2": "#f48024", "3": "#dc3545", "4": "#074590"}');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn(['grafico', 'observaciones', 'colores']);
    }
}
