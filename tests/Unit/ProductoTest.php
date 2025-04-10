<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Producto;
use App\Models\Cliente;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductoTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_producto()
    {
        $producto = Producto::create([
            'nombre' => 'Producto X',
            'descripcion' => 'Descripción del producto X',
        ]);

        $this->assertDatabaseHas('productos', ['nombre' => 'Producto X']);
    }

    public function test_producto_tiene_clientes()
    {
        $producto = Producto::create([
            'nombre' => 'Producto Y',
            'descripcion' => 'Descripción del producto Y',
        ]);

        $cliente = Cliente::create([
            'nombre' => 'Cooperativa Ejemplo',
            'direccion' => 'Valle de los Chillos',
            'telefono' => '2456789',
            'email' => 'cooperativa@gmail.com',
            'contacto' => '234556777',
            'orden_trabajo' => 'orden.pdf',
            'contrato_mantenimiento_licencia' => ' contrato.pdf',
            'documento_otros' => ' documento.pdf',
            'precio' => '13',
            'estado' => 'activo',
        ]);

        // Asociar un cliente con un producto
        $producto->clientes()->attach($cliente->id);


        // Verificar que la relación con cliente funciona
        $this->assertTrue($producto->clientes->contains($cliente));
    }
}
