<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateCompuestosEnmuestrasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compuestos_en_muestras', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();

            $table->unsignedBigInteger('id_compuesto');
            $table->foreign('id_compuesto')
                ->references('id')->on('compuestos_quimicos');

            $table->unsignedBigInteger('id_condiciones');
            $table->foreign('id_condiciones')
                ->references('id')->on('condiciones');

            $table->unsignedBigInteger('id_muestra')->nullable();
            $table->foreign('id_muestra')
                ->references('id')->on('muestras');

            $table->string('cantidad');
            $table->integer('minutos');
            $table->integer('altura');
            $table->integer('inicio');
            $table->integer('fin');
            
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
        Schema::dropIfExists('compuestos_en_muestras');
    }
}
