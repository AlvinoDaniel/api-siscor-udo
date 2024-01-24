<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosExternosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_externos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('correo_destino');
            $table->string('persona_destino');
            $table->string('institucion_destino');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')
            ->references('id')->on('documentos');
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
        Schema::dropIfExists('documentos_externos');
    }
}
