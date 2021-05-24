<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCondicionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('condiciones', function (Blueprint $table) {
            $table->id();
            $table->integer('longitud_columna');
            $table->integer('diametro_interior_columna');
            $table->integer('tamano_particula');
            $table->integer('temperatura');
            $table->integer('velocidad_flujo');
            $table->string('eluyente');
            $table->integer('detector_uv');

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
        Schema::dropIfExists('condiciones');
    }
}
