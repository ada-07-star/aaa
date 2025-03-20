<?php

namespace Database\Factories;

use App\Enums\CurrentStateEnum;
use App\Enums\ParticipationTypeEnum;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Idea>
 */
class IdeaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'topic_id' => Topic::all()->random()->id,
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph,
            'is_published' => $this->faker->boolean,
            'current_state' => $this->faker->randomElement(CurrentStateEnum::values()),
            'participation_type' => $this->faker->randomElement(ParticipationTypeEnum::values()),
            'final_score' => $this->faker->numberBetween(0, 100),
        ];
    }
}
