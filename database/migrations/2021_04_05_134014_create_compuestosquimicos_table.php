<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompuestosQuimicosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('compuestos_quimicos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('formula')->nullable();
            $table->string('descripcion')->nullable();
            $table->string('tipo')->nullable();
            $table->string('estructura')->nullable();
            
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
        Schema::dropIfExists('compuestos_quimicos');
    }
}
