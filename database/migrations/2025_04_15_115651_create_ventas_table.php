<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_venta', ['Interna', 'Externa']);
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            
            // Estados
            $table->enum('estado', [
                'Pendiente', 'Activa', 'Inactiva', 'Expirada', 'Cancelada', 
                'En Curso', 'Finalizada', 'Pausada', 'En Implementacion', 'Cotizacion'
            ])->default('Pendiente');
            
            $table->enum('estado_comercial', [
                'Prospección', 'Contacto', 'Presentación', 'Propuesta', 
                'Negociación', 'Cierre'
            ])->default('Prospección');
            
            // Fechas importantes
            $table->date('fecha_venta')->nullable();
            $table->date('fecha_contrato')->nullable();
            $table->date('fecha_expiracion')->nullable();
            
            // Información financiera básica
            $table->decimal('monto_total', 12, 2)->nullable();
            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->enum('estado_pago', ['pendiente', 'parcial', 'completo'])->default('pendiente');
            
            // Descuentos
            $table->decimal('descuento', 5, 2)->default(0);
            $table->string('porcentaje_descuento_aprobado_por')->nullable();
            
            // Método de pago
            $table->string('metodo_pago')->nullable();
            $table->enum('tipo_contrato', ['arrendamiento', 'adquisicion', 'ambos'])->default('adquisicion');
            
            // Observaciones
            $table->text('observaciones')->nullable();
            
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
        Schema::dropIfExists('ventas');
    }
}
