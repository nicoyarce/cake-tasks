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
            $table->json('colores');
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
