<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentosDepartamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documentos_departamentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('documento_id')->unsigned();
            $table->foreign('documento_id')
            ->references('id')->on('documentos');
            $table->integer('departamento_id')->unsigned();
            $table->foreign('departamento_id')
            ->references('id')->on('departamentos');
            $table->boolean('leido')->default(false);
            $table->boolean('copia')->default(false);
            $table->date('fecha_leido')->nullable()->default(null);
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
        Schema::dropIfExists('documentos_departamentos');
    }
}
