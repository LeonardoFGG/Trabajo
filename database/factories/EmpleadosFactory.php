<?php

namespace Database\Factories;

use App\Models\Empleados;
use App\Models\Departamento; // Asegúrate de importar el modelo Departamento
use App\Models\User; // Asegúrate de importar el modelo User
use Illuminate\Database\Eloquent\Factories\Factory;

class EmpleadosFactory extends Factory
{
    protected $model = Empleados::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'nombre1' => $this->faker->firstName(),
            'apellido1' => $this->faker->lastName(),
            'nombre2' => $this->faker->firstName(),
            'apellido2' => $this->faker->lastName(),
            'cedula' => $this->faker->unique()->numerify('##########'),
            'fecha_nacimiento' => $this->faker->date(),
            'telefono' => $this->faker->phoneNumber(),
            'celular' => $this->faker->phoneNumber(),
            'correo_institucional' => $this->faker->unique()->safeEmail(),
            'departamento_id' => Departamento::factory(), // Crea un nuevo departamento o proporciona un ID existente
            'curriculum' => null,
            'contrato' => null,
            'contrato_confidencialidad' => null,
            'contrato_consentimiento' => null,
            'fecha_ingreso' => now(),
        ];
    }
}
