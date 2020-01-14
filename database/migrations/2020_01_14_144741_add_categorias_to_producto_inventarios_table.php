<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoriasToProductoInventariosTable extends Migration
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
            $table->unsignedBigInteger('categoria_productos_id')->after('unidad');
            $table->foreign('categoria_productos_id')->references('id')->on('categoria_productos');
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
