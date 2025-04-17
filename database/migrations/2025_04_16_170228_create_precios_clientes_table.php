<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('precios_clientes', function (Blueprint $table) {
            $table->id();
            
            // Relaciones
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('cascade');
            $table->foreignId('paquete_id')->nullable()->constrained('paquetes')->onDelete('cascade');
            
            // Datos de precio
            $table->decimal('precio', 10, 2);
            $table->string('moneda', 3)->default('USD');
            $table->date('vigente_desde')->nullable();
            $table->date('vigente_hasta')->nullable();
            
            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
            
            // Restricciones
            $table->unique(['cliente_id', 'producto_id', 'paquete_id']);
            
          
        });
    }

    public function down()
    {
        Schema::dropIfExists('precios_clientes');
    }
};