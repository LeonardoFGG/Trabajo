<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Cliente;
use App\Models\Actividades;
use App\Models\Producto;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ClienteTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_cliente()
    {
        $cliente = Cliente::create([
            'nombre' => 'Cooperativa Ejemplo',
            'direccion' => 'Valle de los Chillos',
            'telefono' => '2456789',
            'email' => 'cooperativa@gmail.com',
            'contacto' => '234556777',
            'orden_trabajo' => 'orden.pdf',
            'contrato_mantenimiento_licencia' => ' contrato.pdf',
            'documento_otros' => ' documento.pdf',
            'precio' => 13,
            'estado' => 'activo',
        ]);

        $this->assertDatabaseHas('clientes', ['nombre' => 'Cooperativa Ejemplo']);


    }

   

    public function test_relacion_con_productos()
    {
        $cliente = Cliente::factory()->create();
        $producto = Producto::factory()->create();

        // Asociar un producto con un cliente
        $cliente->productos()->attach($producto->id);

        $this->assertTrue($cliente->productos->contains($producto));
    }


}

