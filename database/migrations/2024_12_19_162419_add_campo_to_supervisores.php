<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCampoToSupervisores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('supervisores', function (Blueprint $table) {
            // Agregar el campo supervisor_id
            $table->foreignId('supervisor_id')
                  ->nullable()  // Puede ser NULL si no hay un supervisor superior
                  ->constrained('supervisores')  // Hace referencia a la misma tabla
                  ->onDelete('set null')  // Si se elimina un supervisor superior, se pone NULL
                  ->after('empleado_id');  // Colocamos el campo después del empleado_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supervisores', function (Blueprint $table) {
            // Eliminar el campo supervisor_id
            $table->dropForeign(['supervisor_id']);
            $table->dropColumn('supervisor_id');
        });
    }
}
