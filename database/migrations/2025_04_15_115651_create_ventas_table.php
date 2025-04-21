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
            $table->foreignId('producto_id')->nullable()->constrained('productos')->onDelete('cascade');
            $table->foreignId('paquete_id')->nullable()->constrained('paquetes')->onDelete('cascade');
            $table->enum('tipo_item_venta', ['producto', 'paquete'])->default('producto');

            $table->date('fecha_venta')->nullable(); // Cambiado a nullable
            $table->date('fecha_contrato')->nullable();
            $table->date('fecha_implementacion')->nullable();
            $table->date('fecha_expiracion')->nullable();
            $table->enum('estado', ['Pendiente', 'Activa', 'Inactiva', 'Expirada', 'Cancelada', 'En Curso', 'Finalizada', 'Pausada', 'En Implementacion', 'Cotizacion'])->default('Pendiente'); // Añadido Pausada y default
            $table->enum('estado_comercial', ['Prospección', 'Contacto', 'Presentación', 'Propuesta', 'Negociación', 'Cierre Ganado', 'Cierre Perdido'])->default('Prospección'); // Añadido default
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->date('fecha_cobro')->nullable();
            $table->string('anexo_contrato')->nullable();
            $table->text('observaciones')->nullable();
            $table->text('detalle_propuesta')->nullable();
            $table->text('detalle_negociacion')->nullable();

            $table->date('fecha_agenda')->nullable();
            $table->text('detalle_contacto')->nullable();
            $table->date('fecha_presentacion')->nullable();
            $table->text('observacion_presentacion')->nullable();
            $table->string('archivo_propuesta')->nullable();
            $table->string('archivo_negociacion')->nullable();

            $table->string('canal_comunicacion')->nullable(); // email, llamada, redes sociales
            $table->dateTime('fecha_ultimo_contacto')->nullable();
            $table->dateTime('fecha_respuesta_cliente')->nullable();
            $table->date('fecha_encuesta_satisfaccion')->nullable();

            $table->date('fecha_inicio_implementacion')->nullable();
            $table->text('detalle_implementacion')->nullable();
            $table->date('fecha_inicio_implementacion')->nullable();
            $table->date('fecha_fin_implementacion')->nullable();

            $table->enum('tipo_financiamiento', ['contado', 'credito'])->default('contado');

            $table->date('fecha_inicio_garantia')->nullable();
            $table->date('fecha_fin_garantia')->nullable();

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
