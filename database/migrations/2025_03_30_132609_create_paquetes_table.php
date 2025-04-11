<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaquetesTable extends Migration
{
    public function up()
    {
        Schema::create('paquetes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 255);
            $table->string('codigo', 50)->comment('Ej: SEG1, SEG3, SEG4');
            $table->text('descripcion')->nullable();
            $table->decimal('precio_base', 12, 2);
            $table->boolean('activo')->default(true);
            $table->foreignId('sistema_id')->nullable()->constrained('productos')->comment('Sistema principal al que pertenece');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paquetes');
    }
}