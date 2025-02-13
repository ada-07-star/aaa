<?php

namespace Database\Factories;

use App\Models\IdeaRating;
use Illuminate\Database\Eloquent\Factories\Factory;

class IdeaRatingFactory extends Factory
{
    /**
     *
     *
     * @var string
     */
    protected $model = IdeaRating::class;

    /**
     *
     *
     * @return array
     */
    public function definition()
    {
        return [
            'idea_id' => \App\Models\Idea::factory(),
            'rate_number' => $this->faker->numberBetween(1, 5),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}