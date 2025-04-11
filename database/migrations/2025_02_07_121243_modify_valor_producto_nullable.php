<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyValorProductoNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('valor_producto')->nullable()->change();  // Hacer que la columna acepte nulos
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('valor_producto')->nullable(false)->change();  // No permitir nulos
        });
    }
}
