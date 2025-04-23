<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentasTable extends Migration
{
    public function up()
    {
        Schema::create('ventas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_venta', ['Interna', 'Externa']);
            
            // Información básica
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            
            // Estado y progreso
            $table->enum('estado_comercial', [
                'Prospección', 
                'Contacto',
                'Presentación', 
                'Propuesta',
                'Negociación', 
                'Cierre'
            ])->default('Prospección');
            
            $table->enum('estado', [
                'Pendiente', 
                'Activa', 
                'Inactiva', 
                'Expirada', 
                'Cancelada', 
                'En Curso', 
                'Finalizada', 
                'Pausada'
            ])->default('Pendiente');
            
            // Relación con productos/paquetes
            $table->enum('tipo_item_venta', ['producto', 'paquete'])->nullable();
            $table->foreignId('producto_id')->nullable()->constrained('productos');
            $table->foreignId('paquete_id')->nullable()->constrained('paquetes');
            
            // Campos específicos por estado comercial
            // Prospección
            $table->text('detalle_prospeccion')->nullable();
            
            // Contacto
            $table->date('fecha_contacto')->nullable();
            $table->text('detalle_contacto')->nullable();
            $table->string('canal_comunicacion')->nullable();
            
            // Presentación
            $table->date('fecha_presentacion')->nullable();
            $table->text('observacion_presentacion')->nullable();
            
            // Propuesta
            $table->date('fecha_propuesta')->nullable();
            $table->string('archivo_propuesta')->nullable();
            $table->text('detalle_propuesta')->nullable();
            
            // Negociación
            $table->date('fecha_negociacion')->nullable();
            $table->string('archivo_negociacion')->nullable();
            $table->text('detalle_negociacion')->nullable();
            
            // Cierre
            $table->date('fecha_venta')->nullable();
            $table->date('fecha_contrato')->nullable();
            $table->date('fecha_cobro')->nullable();
            $table->date('fecha_expiracion')->nullable();
            $table->string('anexo_contrato')->nullable();
            
            $table->timestamps();
        });

        // Tabla pivote para productos en ventas (permite múltiples productos)
        Schema::create('producto_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Tabla pivote para paquetes en ventas (permite múltiples paquetes)
        Schema::create('paquete_venta', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venta_id')->constrained('ventas')->onDelete('cascade');
            $table->foreignId('paquete_id')->constrained('paquetes')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paquete_venta');
        Schema::dropIfExists('producto_venta');
        Schema::dropIfExists('ventas');
    }
}