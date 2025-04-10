<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Empleados;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */

    public function test_crear_usuario()
    {
        // Crear un usuario
        $user = User::create([
            'name' => 'User',
            'email' => 'user@gmail.com',
            'password' => bcrypt('password123'),
            'role' => 'empleado',
        ]);
         
        // Verificar que el usuario está en la base de datos
        $this->assertDatabaseHas('users', ['name' => 'User']);
    }

    public function test_user_is_admin()
    {
        // Crear un usuario con el rol de admin
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@empresa.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        // Verificar que el usuario es admin
        $this->assertTrue($user->isAdmin());
        $this->assertFalse($user->isEmpleado());
    }

    /** @test */
    public function test_user_is_empleado()
    {
        // Crear un usuario con el rol de empleado
        $user = User::create([
            'name' => 'Empleado User',
            'email' => 'empleado@empresa.com',
            'password' => bcrypt('password123'),
            'role' => 'empleado',
        ]);

        // Verificar que el usuario es empleado
        $this->assertTrue($user->isEmpleado());
        $this->assertFalse($user->isAdmin());
    }

    
}
