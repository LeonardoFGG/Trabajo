<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Departamento;
use App\Models\Empleados;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartamentoTest extends TestCase
{
    use RefreshDatabase;

    public function test_crear_departamento()
    {
        // Crear un departamento
        $departamento = Departamento::create([
            'nombre' => 'Marketing',
            'descripcion' => 'Departamento de marketing',
        ]);

        // Verificar que el departamento está en la base de datos
        $this->assertDatabaseHas('departamentos', ['nombre' => 'Marketing']);
    }

    public function test_departamento_tiene_empleados()
    {
        // Crear un departamento
        $departamento = Departamento::create([
            'nombre' => 'Desarrollo',
            'descripcion' => 'Departamento de desarrollo',
        ]);


        // Crear un empleado
        $empleado = Empleados::create([
            'nombre1' => 'Juan',
            'apellido1' => 'Pérez',
            'nombre2' => 'Carlos',
            'apellido2' => 'Gómez',
            'cedula' => '1234567890',
            'fecha_nacimiento' => '1990-05-10',
            'telefono' => '0998765432',
            'celular' => '0987654321',
            'correo_institucional' => 'empleado@empresa.com',
            'departamento_id' => $departamento->id,
            'curriculum' => 'cv.pdf',
            'contrato' => 'contrato.pdf',
            'contrato_confidencialidad' => 'confidencialidad.pdf',
            'contrato_consentimiento' => 'consentimiento.pdf',
            'fecha_ingreso' => '2022-01-15',
        ]);

        // Asociar un empleado con un departamento
        $departamento->empleados()->attach($empleado->id);

        //verificar que la relación con empleado funciona
        $this->assertTrue($departamento->empleados->contains($empleado));
}
}
