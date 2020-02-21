<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdenComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orden_compras', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->text('asunto');
            $table->text('descripcion');
            $table->text('cantidad');
            $table->text('unidad_medida')->nullable()->default(null);
            $table->text('valor_unitario');
            $table->text('estado_producto')->nullable()->default(null);
            $table->integer('valor_neto');
            $table->integer('iva');
            $table->integer('total');
            $table->text('observacion')->nullable()->default(null);
            $table->string('fecha');

            $table->unsignedBigInteger('proveedor_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');

            $table->unsignedBigInteger('lugares_id')->nullable()->default(null);
            $table->foreign('lugares_id')->references('id')->on('lugares');

            $table->unsignedBigInteger('user_id');
            //$table->foreign('user_id')->references('id')->on('users');

            $table->softDeletes();
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
        Schema::dropIfExists('orden_compras');
    }
}
