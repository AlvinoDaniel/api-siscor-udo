<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres_apellidos');
            $table->string('cedula_identidad');
            $table->string('cargo');
            $table->integer('cod_nucleo')->unsigned();
            $table->foreign('cod_nucleo')
            ->references('id')->on('nucleo');
            $table->boolean('jefe')->default(0);
            $table->string('descripcion_cargo')->nullable();
            $table->string('correo')->nullable();
            $table->string('firma')->nullable();
            $table->integer('departamento_id')->unsigned();;
            $table->foreign('departamento_id')
            ->references('id')->on('departamentos');
            $table->integer('nivel_id')->unsigned();;
            $table->foreign('nivel_id')
            ->references('id')->on('nivel')->nullable();
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
        Schema::dropIfExists('personal');
    }
}
