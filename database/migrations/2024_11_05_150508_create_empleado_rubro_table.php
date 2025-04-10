<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpleadoRubroTable extends Migration
{
    public function up()
    {
        Schema::create('empleado_rubro', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empleado_id');
            $table->unsignedBigInteger('rubro_id');
            $table->decimal('monto', 10, 2)->nullable();
            $table->timestamps();
        
            $table->foreign('empleado_id')->references('id')->on('empleados')->onDelete('cascade');
            $table->foreign('rubro_id')->references('id')->on('rubros')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('empleado_rubro');
    }
};