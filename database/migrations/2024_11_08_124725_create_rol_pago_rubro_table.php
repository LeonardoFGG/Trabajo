<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolPagoRubroTable extends Migration
{
    public function up()
    {
        Schema::create('rol_pago_rubro', function (Blueprint $table) {
            $table->id();
            // Asegúrate de que la columna correcta se llame 'rol_pago_id'
            $table->unsignedBigInteger('rol_pago_id');
            $table->unsignedBigInteger('rubro_id');
            $table->decimal('monto', 10, 2)->nullable();
            $table->timestamps();

            // Claves foráneas
            $table->foreign('rol_pago_id')->references('id')->on('roles_pago')->onDelete('cascade');
            $table->foreign('rubro_id')->references('id')->on('rubros')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rol_pago_rubro');
    }
}
