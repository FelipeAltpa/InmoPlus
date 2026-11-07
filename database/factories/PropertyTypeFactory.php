<?php

namespace Database\Factories;

use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyTypeFactory extends Factory
{
    protected $model = PropertyType::class;

    public function definition(): array
    {
        $types = [
            ['name' => 'Casa', 'description' => 'Propiedad residencial unifamiliar'],
            ['name' => 'Departamento', 'description' => 'Unidad habitacional en edificio'],
            ['name' => 'Terreno', 'description' => 'Lote o parcela de terreno'],
            ['name' => 'Local Comercial', 'description' => 'Espacio para uso comercial'],
            ['name' => 'Oficina', 'description' => 'Espacio para uso profesional'],
            ['name' => 'Bodega', 'description' => 'Espacio para almacenamiento'],
        ];

        $type = $this->faker->randomElement($types);

        return [
            'name' => $type['name'],
            'description' => $type['description'],
            'status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}