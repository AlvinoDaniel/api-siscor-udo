<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosTemporalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_temporal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')
            ->references('id')->on('documentos');
            $table->string('departamentos_destino');
            $table->string('departamentos_copias')->nullable();
            $table->boolean('tieneCopia')->default(false);
            $table->boolean('leido')->default(false);
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
        Schema::dropIfExists('documentos_temporal');
    }
}
