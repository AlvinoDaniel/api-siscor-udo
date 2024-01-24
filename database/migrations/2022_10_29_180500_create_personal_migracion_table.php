<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonalMigracionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('personal_migracion', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nombres');
            $table->string('cedula_identidad');
            $table->string('cargo');
            $table->string('cod_nucleo',2);
            $table->string('correo')->nullable();
            $table->string('grado_instruccion')->nullable();
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
        Schema::dropIfExists('personal_migracion');
    }
}
