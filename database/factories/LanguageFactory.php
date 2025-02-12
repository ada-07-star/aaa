<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Language>
 */
class LanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'short_name' => $this->faker->unique()->lexify('???'),
            'name' => $this->faker->word(),
            'is_active' => $this->faker->boolean(),
            'created_by' => $this->faker->randomDigitNotNull(),
            'updated_by' => $this->faker->randomDigitNotNull(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
