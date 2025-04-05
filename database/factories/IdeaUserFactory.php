<?php

namespace Database\Factories;

use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IdeaUser>
 */
class IdeaUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'idea_id' => Idea::pluck('id')->random(),
            'user_id' => User::pluck('id')->random(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
