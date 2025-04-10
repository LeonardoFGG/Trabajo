<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Actividades;
use App\Models\Empleados;
use App\Models\Cliente;
use App\Models\Departamento;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ActividadesTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_actividad()
    {
        
    }

    public function test_relacion_con_empleado()
    {
        // Creando el modelo de empleado manualmente
        $empleado = new Empleados();
        $empleado->nombre1 = 'Juan Pérez';
        $empleado->email = 'juan.perez@example.com';
        $empleado->save();

        // Creando la actividad y asignando el empleado
        $actividad = new Actividades();
        $actividad->empleado_id = $empleado->id;
        $actividad->descripcion = 'Revisar software';
        $actividad->save();

        // Verificar que la relación con empleado funciona
        $this->assertInstanceOf(Empleados::class, $actividad->empleado);
    }

    public function test_relacion_con_cliente()
    {
        // Creando el modelo de cliente manualmente
        $cliente = new Cliente();
        $cliente->nombre = 'Cliente ABC';
        $cliente->email = 'cliente.abc@example.com';
        $cliente->save();

        // Creando la actividad y asignando el cliente
        $actividad = new Actividades();
        $actividad->cliente_id = $cliente->id;
        $actividad->descripcion = 'Revisar software';
        $actividad->save();

        // Verificar que la relación con cliente funciona
        $this->assertInstanceOf(Cliente::class, $actividad->cliente);
    }

    public function test_relacion_con_departamento()
    {
        // Creando el modelo de departamento manualmente
        $departamento = new Departamento();
        $departamento->nombre = 'Desarrollo';
        $departamento->save();

        // Creando la actividad y asignando el departamento
        $actividad = new Actividades();
        $actividad->departamento_id = $departamento->id;
        $actividad->descripcion = 'Revisar software';
        $actividad->save();

        // Verificar que la relación con departamento funciona
        $this->assertInstanceOf(Departamento::class, $actividad->departamento);
    }
}
