<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('numero_factura')->nullable()->default(null);

            $table->string('fecha')->nullable()->default(null);;
            $table->text('observacion')->nullable()->default(null);

            $table->unsignedBigInteger('orden_compras_id')->nullable()->default(null);
            $table->foreign('orden_compras_id')->references('id')->on('orden_compras');

            $table->unsignedBigInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');

            $table->unsignedBigInteger('lugares_id')->nullable()->default(null);
            $table->foreign('lugares_id')->references('id')->on('lugares');

            $table->unsignedBigInteger('user_id')->nullable()->default(null);
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
        Schema::dropIfExists('facturas');
    }
}
