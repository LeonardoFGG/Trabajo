<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseDocumentoOtrosLength extends Migration
{
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->text('documento_otros')->change(); // Cambia a 'text' para longitud ilimitada
        });
    }

    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->string('documento_otros', 255)->change(); // Vuelve a cambiarlo si es necesario
        });
    }
}
