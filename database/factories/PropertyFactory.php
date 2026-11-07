<?php

namespace Database\Factories;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    protected $model = Property::class;

    public function definition(): array
    {
        $operationType = $this->faker->randomElement(['venta', 'alquiler']);
        $status = $this->faker->randomElement(['disponible', 'reservado', 'vendido', 'alquilado']);

        return [
            'property_type_id' => PropertyType::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(3),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'neighborhood' => $this->faker->randomElement(['Centro', 'Norte', 'Sur', 'Este', 'Oeste', 'Residencial', 'Comercial']),
            // Venta es precio total; alquiler es precio mensual, ambos en COP.
            'price' => $operationType === 'venta'
                ? $this->faker->numberBetween(150, 5000) * 1000000
                : $this->faker->numberBetween(15, 300) * 100000,
            'area' => $this->faker->randomFloat(2, 50, 500),
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'operation_type' => $operationType,
            'status' => $status,
            'main_image' => null,
        ];
    }

    public function forSale(): static
    {
        return $this->state(fn (array $attributes) => [
            'operation_type' => 'venta',
        ]);
    }

    public function forRent(): static
    {
        return $this->state(fn (array $attributes) => [
            'operation_type' => 'alquiler',
        ]);
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'disponible',
        ]);
    }
}
