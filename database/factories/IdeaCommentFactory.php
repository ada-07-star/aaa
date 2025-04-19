<?php

namespace Database\Factories;

use App\Models\IdeaComment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class IdeaCommentFactory extends Factory
{
    /**
     *
     *
     * @var string
     */
    protected $model = IdeaComment::class;

    /**
     * 
     *
     * @return array
     */
    public function definition()
    {
        return [
            'comment_text' => $this->faker->paragraph,
            'idea_id' => \App\Models\Idea::all()->random()->id,
            'parent_id' => null,
            'likes' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['published', 'draft']),
            'created_by' =>\App\Models\User::all()->random()->id,
        ];
    }
}
