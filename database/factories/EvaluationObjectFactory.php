<?php

namespace Database\Factories;

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
    public function definition(): array
    {
        return [
            'evaluation_id' => \App\Models\Evaluation::all()->random()->id,
            'object_id' => \App\Models\ObjectModel::all()->random()->id,
            'order_of' => $this->faker->numberBetween(1, 10),
        ];
    }
}
