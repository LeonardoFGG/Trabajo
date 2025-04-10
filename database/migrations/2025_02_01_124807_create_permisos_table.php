<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermisosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade'); // Relación con empleados
            $table->date('fecha_solicitud'); // Fecha en la que se solicita el permiso
            $table->date('fecha_salida'); // Fecha de salida
            $table->time('hora_salida'); // Hora de salida
            $table->time('hora_regreso')->nullable(); // Hora de regreso (editable)
            $table->time('duracion')->nullable(); // Duración calculada automáticamente
            $table->string('tipo_permiso'); // Tipo de permiso (Ej: Médico, Personal, Otro)
            $table->string('anexos')->nullable(); // Anexos (documentos adjuntos)
            $table->text('motivo')->nullable(); // Motivo (solo si el tipo es "Otro")
            $table->enum('estado', ['Pendiente', 'Aprobado', 'Rechazado']); // Estado de la solicitud
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
        Schema::dropIfExists('permisos');
    }
}
