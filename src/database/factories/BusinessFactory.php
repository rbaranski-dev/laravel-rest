<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Business>
 */
class BusinessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'number' => $this->faker->numerify('###########'),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'zip_code' => $this->faker->postcode(),
        ];
    }
}
