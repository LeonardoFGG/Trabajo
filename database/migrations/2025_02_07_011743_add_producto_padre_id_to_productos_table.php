<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductoPadreIdToProductosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Agregar el campo producto_padre_id
            $table->unsignedBigInteger('producto_padre_id')->nullable()->after('valor_producto');

            // Definir la clave foránea
            $table->foreign('producto_padre_id')
                  ->references('id')
                  ->on('productos')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            // Eliminar la clave foránea
            $table->dropForeign(['producto_padre_id']);

            // Eliminar el campo producto_padre_id
            $table->dropColumn('producto_padre_id');
        });
    }
}