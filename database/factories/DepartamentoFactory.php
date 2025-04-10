<?php

namespace Database\Factories;

use App\Models\Departamento;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartamentoFactory extends Factory
{
    protected $model = Departamento::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->word(), // Nombre del departamento
            'descripcion' => $this->faker->sentence(), // Descripción del departamento
        ];
    }
}
