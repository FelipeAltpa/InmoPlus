<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    protected $model = Client::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('55-####-####'),
            'identification' => fake()->unique()->numerify('A-####-#####-###'),
            'address' => fake()->streetAddress(),
            'city' => fake()->randomElement([
                'Bogotá',
                'Medellín',
                'Cali',
                'Cartagena',
                'Bucaramanga',
                'Pereira',
                'Barranquilla',
                'Manizales',
            ]),
            'status' => 'active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function withUser(): static
    {
        return $this->state(function (array $attributes) {
            $user = User::factory()->create([
                'name' => $attributes['name'],
                'email' => $attributes['email'],
                'role' => 'client',
            ]);

            return ['user_id' => $user->id];
        });
    }
}
