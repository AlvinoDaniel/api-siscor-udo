<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarpetasDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carpertas_documentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')
            ->references('id')->on('documentos');
            $table->integer('carpeta_id')->unsigned();
            $table->foreign('carpeta_id')
            ->references('id')->on('carpetas');
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
        Schema::dropIfExists('carpertas_documentos');
    }
}
