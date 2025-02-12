<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'descriptions' => $this->faker->paragraph,
            'status' => $this->faker->boolean,
            'created_by' => $this->faker->numberBetween(1, 10),
            'updated_by' => $this->faker->numberBetween(1, 10)
        ];
    }
}
