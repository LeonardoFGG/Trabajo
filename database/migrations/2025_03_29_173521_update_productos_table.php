<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateProductosTable extends Migration
{
    public function up()
    {
        // Cambiar el tipo de dato de valor_producto a decimal
        Schema::table('productos', function (Blueprint $table) {
            $table->decimal('valor_producto', 12, 2)->nullable()->change();
        });

        // Agregar nuevos campos necesarios
        Schema::table('productos', function (Blueprint $table) {
            $table->string('codigo', 50)->nullable()->after('nombre');
            $table->string('tipo', 50)->after('descripcion')->comment('core, modulo, servicio, estructura, proceso,aplicacion');
            $table->string('categoria', 100)->nullable()->after('tipo');
            $table->boolean('incluido_en_paquete')->default(false)->after('valor_producto');
            $table->enum('periodicidad_cobro', ['diario', 'mensual', 'anual'])->after('incluido_en_paquete');
            $table->boolean('activo')->default(true)->after('periodicidad_cobro');
            $table->string('version', 20)->nullable()->after('nombre')->comment('Para sistemas como Banelweb 1.0, 2.0, etc.');
            $table->string('modalidad_servicio', 50)->nullable()->comment = ('Modalidad del servicio (remoto, presencial)');

        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['codigo', 'tipo', 'categoria', 'incluido_en_paquete', 'periodicidad_cobro', 'activo', 'version', 'modalidad_servicio']);
            $table->integer('valor_producto')->nullable(false)->change();
        });
    }
}