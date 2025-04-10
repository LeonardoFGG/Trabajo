<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesPagoTable extends Migration
{
    public function up()
    {
        Schema::create('roles_pago', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('total_ingreso', 10, 2)->default(0);
            $table->decimal('total_egreso', 10, 2)->default(0);
            $table->decimal('salario_neto', 10, 2)->default(0);
            $table->timestamps();

            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles_pago');
    }
};
