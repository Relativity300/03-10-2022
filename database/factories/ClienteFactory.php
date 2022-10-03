<?php

namespace Database\Factories;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
			'Nombre' => $this->faker->name,
			'Direccion' => $this->faker->name,
			'telefono' => $this->faker->name,
			'rol' => $this->faker->name,
        ];
    }
}
