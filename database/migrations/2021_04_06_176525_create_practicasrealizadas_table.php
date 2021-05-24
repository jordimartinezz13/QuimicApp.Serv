<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePracticasrealizadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('practicas_realizadas', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('id_practica')->nullable();
            $table->foreign('id_practica')
                ->references('id')->on('practicas');

            $table->unsignedBigInteger('id_grupo')->nullable();
            $table->foreign('id_grupo')
                ->references('id')->on('grupos');

            $table->string('respuesta_alumno')->nullable();
            $table->integer('nota')->nullable();
            $table->string('comentario_alumno')->nullable();
            $table->string('comentario_profesor')->nullable();
            $table->string('fichero')->nullable();
            $table->boolean('puede_proceder')->nullable();

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
        Schema::dropIfExists('practicas_realizadas');
    }
}
