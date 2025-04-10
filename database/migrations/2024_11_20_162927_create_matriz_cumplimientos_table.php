<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatrizCumplimientosTable extends Migration
{
    public function up()
    {
        Schema::create('matriz_cumplimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parametro_id')->constrained('parametros')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained('cargos')->onDelete('cascade');
            $table->foreignId('supervisor_id')->constrained('supervisores')->onDelete('cascade');
			$table->decimal('puntos', 3, 1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('matriz_cumplimientos');
    }
}
