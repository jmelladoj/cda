<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_stocks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('detalle');
            $table->integer('cantidad');
            $table->smallInteger('accion');
            $table->integer('valor_ingreso');

            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')->references('id')->on('producto_inventarios');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

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
        Schema::dropIfExists('detalle_stocks');
    }
}
