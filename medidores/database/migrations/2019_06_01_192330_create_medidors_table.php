<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedidorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medidores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('voltaje');
            $table->integer('corriente');
            $table->integer('potencia');
            $table->integer('factor_potencia');
            $table->integer('frecuencia');
            $table->integer('energia_entrega');
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
        Schema::dropIfExists('medidors');
    }
}
