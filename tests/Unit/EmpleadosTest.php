<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Empleados;
use App\Models\Departamento;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmpleadosTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_crear_empleado()
    {
        // Crear un departamento
        $departamento = Departamento::create([
            'nombre' => 'Desarrollo',
            'descripcion' => 'Departamento de desarrollo',
        ]);

        // Crear un usuario para el empleado
        $user = User::create([
            'name' => 'Juan Pérez',
            'email' => 'juan.perez@gmail.com',
            'password' => bcrypt('1234567890'),
            'role' => 'empleado', // Establecer el rol como 'empleado'
        ]);

        // Verificar que el usuario se haya creado
        $this->assertNotNull($user->id);

        // Crear el empleado
        $empleado = Empleados::create([
            'user_id' => $user->id, // Asegurarse de que user_id se esté asignando
            'nombre1' => 'Juan',
            'apellido1' => 'Pérez',
            'nombre2' => 'Carlos',
            'apellido2' => 'Gómez',
            'cedula' => '1234567890',
            'fecha_nacimiento' => '1990-05-10',
            'telefono' => '0998765432',
            'celular' => '0987654321',
            'correo_institucional' => 'juan.perez@gmail.com',
            'departamento_id' => $departamento->id, // Usar el ID del departamento
            'curriculum' => 'cv.pdf',
            'contrato' => 'contrato.pdf',
            'contrato_confidencialidad' => 'confidencialidad.pdf',
            'contrato_consentimiento' => 'consentimiento.pdf',
            'fecha_ingreso' => '2022-01-15',
        ]);

        // Verificar que el empleado se haya creado en la base de datos
        $this->assertDatabaseHas('empleados', [
            'nombre1' => 'Juan',
            'apellido1' => 'Pérez',
            'cedula' => '1234567890',
        ]);

        // Verificar que el empleado tenga el usuario asociado
        $this->assertEquals($user->id, $empleado->user_id);
    }
}
