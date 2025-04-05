<?php

namespace Database\Factories;

use App\Models\EvaluationObject;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EvaluationObject>
 */
class EvaluationObjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = EvaluationObject::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'department_id' => \App\Models\Department::factory(),
            'description' => $this->faker->paragraph,
            'status' => $this->faker->boolean,
            'created_by' => \App\Models\User::factory(),
        ];
    }
}
