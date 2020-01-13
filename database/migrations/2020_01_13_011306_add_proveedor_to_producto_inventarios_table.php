<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProveedorToProductoInventariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('producto_inventarios', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('proveedor_id')->nullable()->default(null)->after('categoria_producto_id');
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('producto_inventarios', function (Blueprint $table) {
            //
        });
    }
}
