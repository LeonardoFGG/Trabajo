<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_cargo');
            $table->string('descripcion')->nullable(); // Permitir que sea nulo si es necesario
            $table->string('codigo_afiliacion');
            $table->integer('salario_basico');
            // Relación uno a muchos con Departamentos
            $table->foreignId('departamento_id')->constrained('departamentos')->onDelete('cascade')->nullable(); // Permitir que sea nulo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargos', function (Blueprint $table) {
            $table->dropForeign(['departamento_id']); // Eliminar la clave foránea antes de eliminar la tabla
        });
        
        Schema::dropIfExists('cargos');
    }
}
