<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaqueteProductoTable extends Migration
{
    public function up()
    {
        Schema::create('paquete_producto', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paquete_id')->constrained('paquetes')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            //$table->integer('cantidad')->default(1);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paquete_producto');
    }
}

