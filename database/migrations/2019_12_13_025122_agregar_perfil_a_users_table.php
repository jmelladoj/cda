<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AgregarPerfilAUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('perfil_id')->nullable()->default(NULL)->after('password');
            $table->foreign('perfil_id')->references('id')->on('perfiles');

            $table->string('ubicacion')->null()->default(null)->after('perfil_id');
        });
    }
}
