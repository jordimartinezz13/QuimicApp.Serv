<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practicas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_profesor');
            $table->foreign('id_profesor')
                ->references('id')->on('profesores');

            $table->unsignedBigInteger('id_compuesto_en_muestra');
            $table->foreign('id_compuesto_en_muestra')
                ->references('id')->on('compuestos_en_muestras');

            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            
            $table->string('enunciado')->nullable();

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
        Schema::dropIfExists('practicas');
    }
}
