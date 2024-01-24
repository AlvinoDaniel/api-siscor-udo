<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->mediumText('asunto');
            $table->bigInteger('nro_documento')->nullable();
            $table->longText('contenido');
            $table->string('tipo_documento');
            $table->string('estatus');
            $table->dateTime('fecha_enviado')->nullable();
            $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')
            ->references('id')->on('departamentos');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')
            ->references('id')->on('users');
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
        Schema::dropIfExists('documentos');
    }
}
