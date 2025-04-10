<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClienteTable extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('telefono');
            $table->string('email');
            $table->string('contacto')->default('Sin contacto');
            $table->string('contrato_implementacion')->nullable();
            $table->string('convenio_datos')->nullable();
            $table->string('documento_otros')->nullable();
            //total del valor de los productos
            $table->integer('total_valor_productos');
            $table->enum('estado', ['ACTIVO', 'INACTIVO']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
}
