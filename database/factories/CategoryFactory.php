<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'department_id' => Department::pluck('id')->random(),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->boolean(),
            'created_by' => $this->faker->randomNumber(), 
            'updated_by' => $this->faker->randomNumber(),
            'created_at' => now(),  
            'updated_at' => now(),
        ];
    }
}
